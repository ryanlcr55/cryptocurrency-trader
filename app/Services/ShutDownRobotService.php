<?php

namespace App\Services;

use App\Exchange\ExchangeBinance;
use App\Models\User;
use App\Models\UserOrderRecord;
use App\Models\UserRunningRobot;
use App\Models\UserRunningRobotHistory; 
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ShutDownRobotService
{
    public function __construct(
        protected User $userModel,
        protected UserRunningRobot $userRunningRobotModel,
        protected UserOrderRecord $userOrderRecordModel,
        protected UserRunningRobotHistory $userRunningRobotHistoryModel,
    ) {
    }

    public function exec(UserRunningRobot $runningRobot, $currentPrice = null)
    {
        $user = $this->userModel->find($runningRobot->user_id);
        if (!$user->exchange_api_key || !$user->exchange_secret_key) {
            Log::error('Failed to exec shutdown robot', [
                'user_id' => $runningRobot->user_id,
                'signal_id' => $runningRobot->signal_id,
                'msg' => 'User api key is not available',
            ]);
            return;
        }

        try {
            DB::beginTransaction();
            $runningRobot = $this->userRunningRobotModel
                ->lockForUpdate()
                ->find($runningRobot->id);

            if (!$runningRobot) {
                DB::rollBack();
                return;
            }

            $exchange = new ExchangeBinance($user->exchange_api_key, $user->exchange_secret_key);
            $runningRobot->update([
                'status' => UserRunningRobot::STATUS_STOPPED,
            ]);

            $sellingQuantity = $this->getSellingQuantity(
                $runningRobot->quantity,
                $runningRobot->starting_price,
                $currentPrice ?? $exchange->getPrice($runningRobot->coin_code . $runningRobot->base_coin_code)
            );
            $tradeResponse = $exchange->sellingTrade(
                $runningRobot->coin_code. $runningRobot->base_coin_code,
                number_format($sellingQuantity, 8)
            );
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to exec shutdown robot', [
                'user_id' => $runningRobot->user_id,
                'signal_id' => $runningRobot->signal_id,
                'code' => $e->getCode(),
                'msg' => $e->getMessage(),
            ]);
            return;
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
            'profit' => bcsub($tradeResponse['cost'], $runningRobot->cost, 18),
            'quantity' => $runningRobot->quantity,
            'fee' =>  bcadd($runningRobot->fee, $tradeResponse['fee'], 18),
            'creating_at' => $runningRobot->created_at,
            'ending_at' => $tradeResponse['order_created_at'],
        ]);
        $runningRobot->delete();
    }

    protected function getSellingQuantity($quantity, $startPrice, $currentPrice)
    {
        return bcmul($quantity, bcdiv($currentPrice, $startPrice), 18);
    }
}
