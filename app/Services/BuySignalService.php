<?php

namespace App\Services;

use App\Exchange\ExchangeBinance;
use App\Models\User;
use App\Models\UserOrderRecord;
use App\Models\UserRobotReference;
use App\Models\UserRunningRobot;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BuySignalService implements SignalActionInterface
{
    public function __construct(
        protected User $userModel,
        protected UserRunningRobot $userRunningRobotModel,
        protected UserOrderRecord $userOrderRecordModel
    ) {
    }

    public function exec(UserRobotReference $robotReference, string $coinCode)
    {
        $symbol = strtoupper($coinCode . $robotReference->base_coin_code);
        if ($this->checkExistedRunningRobot(
            $robotReference->user_id,
            $robotReference->signal_id,
            $coinCode,
            $robotReference->base_coin_code
        )) {
            return;
        }

        $user = $this->userModel->find($robotReference->user_id);
        if (!$user->exchange_api_key || !$user->exchange_secret_key) {
            throw new Exception('User api key is not available');
        }

        try {
            DB::beginTransaction();
            $exchange = new ExchangeBinance($user->exchange_api_key, $user->exchange_secret_key);
            $cost = $this->countCost(
                $exchange->getCoinBalance($robotReference->base_coin_code),
                $robotReference->unit_percent
            );
            $robotUid = Str::orderedUuid()->toString();

            $robot = $this->userRunningRobotModel->create([
                'user_id' => $user->id,
                'signal_id' => $robotReference->id,
                'robot_uid' => $robotUid,
                'coin_code' => $coinCode,
                'base_coin_code' => $robotReference->base_coin_code,
                'cost' => $cost,
            ]);
            $tradeResponse = $exchange->buyingTrade(
                $symbol,
                $cost
            );
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to exec buyAction', [
                'user_id' => $robotReference->user_id,
                'signal_id' => $robotReference->signal_id,
                'code' => $e->getCode(),
                'msg' => $e->getMessage(),
            ]);
            return;
        }

        $this->userOrderRecordModel->create([
            'user_id' => $user->id,
            'robot_uid' => $robotUid,
            'symbol' => $tradeResponse['symbol'],
            'action' => UserOrderRecord::ACTION_BUY,
            'exchange_order_id' => $tradeResponse['order_id'],
            'price' => $tradeResponse['price'],
            'cost' => $tradeResponse['cost'],
            'quantity' => $tradeResponse['quantity'],
            'fee' => bcmul($tradeResponse['fee'], $tradeResponse['price'], 18),
            'order_created_at' => $tradeResponse['order_created_at'],
        ]);

        $robot->update([
            'cost' => $tradeResponse['cost'],
            'quantity' => $tradeResponse['quantity'],
            'starting_price' => $tradeResponse['price'],
            'status' => UserRunningRobot::STATUS_ACTIVED,
            'upper_limit_price' => $this->countLimitPrice(
                $tradeResponse['price'],
                $robotReference->limit_percent
            ),
            'lower_limit_price' => $this->countStopPrice(
                $tradeResponse['price'],
                $robotReference->stop_percent
            ),
        ]);
    }

    protected function checkExistedRunningRobot(int $userId, int $signalId, string $coinCode, string $baseCoinCode)
    {
        return $this->userRunningRobotModel
            ->where('user_id', '=', $userId)
            ->where('signal_id', '=', $signalId)
            ->where('status', '!=', UserRunningRobot::STATUS_STOPPED)
            ->where('coin_code', '=', $coinCode)
            ->where('base_coin_code', '=', $baseCoinCode)
            ->exists();
    }

    protected function countCost(float $userBalance, float $unitPrice)
    {
        return bcmul($userBalance, bcdiv($unitPrice, 100, 18), 8);
    }

    protected function countLimitPrice(float $currentPrice, float $limitPercent)
    {
        return bcadd($currentPrice, bcmul($currentPrice, bcdiv($limitPercent, 100, 18), 18), 8);
    }

    protected function countStopPrice(float $currentPrice, float $stopPercent)
    {
        return bcsub($currentPrice, bcmul($currentPrice, bcdiv($stopPercent, 100, 18), 18), 8);
    }
}
