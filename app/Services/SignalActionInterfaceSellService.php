<?php

namespace App\Services;

use App\Exchange\ExchangeBinance;
use App\Models\User;
use App\Models\UserOrderRecord;
use App\Models\UserRobotReference;
use App\Models\UserRunningRobot;
use App\Models\UserRunningRobotHistory;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SignalActionInterfaceSellService implements SignalActionInterface
{
    public function __construct(
        protected User $userModel,
        protected UserRunningRobot $userRunningRobotModel,
        protected UserOrderRecord $userOrderRecordModel,
        protected UserRunningRobotHistory $userRunningRobotHistoryModel,
    ) {
    }

    public function exec(UserRobotReference $robotReference, string $coinCode)
    {
        $symbol = strtoupper($coinCode . $robotReference->base_coin_code);


        $user = $this->userModel->find($robotReference->user_id);
        if (!$user->exchange_api_key || !$user->exchange_secret_key) {
            throw new Exception('User api key is not available');
        }

        try {
            DB::beginTransaction();
            $runningRobot = $this->getRunningRobot(
                $robotReference->user_id,
                $robotReference->signal_id,
                $coinCode,
                $robotReference->base_coin_code
            );

            if (!$runningRobot) {
                DB::rollBack();
                return;
            }

            $exchange = new ExchangeBinance($user->exchange_api_key, $user->exchange_secret_key);
            $runningRobot->update([
                'status' => UserRunningRobot::STATUS_STOPPED,
            ]);

            $tradeResponse = $exchange->sellingTrade(
                $symbol,
                (string) $runningRobot->quantity
            );
            DB::commit();
        } catch (\Exception $e) {
            Log::error('Failed to exec buyAction, signal_id: ' . $robotReference->signal_id . ', user_id: ' . $robotReference->user_id);
        }
        $this->userOrderRecordModel->create([
            'user_id' => $user->id,
            'robot_uid' => $runningRobot->robot_uid,
            'symbol' => $tradeResponse['symbol'],
            'action' => UserOrderRecord::ACTION_SELL,
            'exchange_order_id' => $tradeResponse['order_id'],
            'price' => $tradeResponse['price'],
            'cost' => $tradeResponse['cost'],
            'quantity' => $tradeResponse['quantity'],
            'fee' => $tradeResponse['fee'],
            'order_created_at' => $tradeResponse['order_created_at'],
        ]);

        $this->userRunningRobotHistoryModel->create([
            'user_id' => $runningRobot->user_id,
            'signal_id' => $runningRobot->signal_id,
            'robot_uid' => $runningRobot->robot_uid,
            'base_coin_code' => $runningRobot->base_coin_code,
            'coin_code' => $runningRobot->coin_code,
            'base_cost' => $runningRobot->cost,
            'starting_price' => $runningRobot->starting_price,
            'ending_price' => $tradeResponse['price'],
            'profit' => $tradeResponse['cost'] - $runningRobot->cost,
            'creating_at' => $runningRobot->created_at,
            'ending_at' => $tradeResponse['order_created_at'],
        ]);
        $runningRobot->delete();
    }

    protected function getRunningRobot(int $userId, int $signalId, string $coinCode, string $baseCoinCode)
    {
        return $this->userRunningRobotModel
            ->where('user_id', '=', $userId)
            ->where('signal_id', '=', $signalId)
            ->where('status', '=', UserRunningRobot::STATUS_ACTIVED)
            ->where('coin_code', '=', $coinCode)
            ->where('base_coin_code', '=', $baseCoinCode)
            ->lockForUpdate()
            ->first();
    }
}
