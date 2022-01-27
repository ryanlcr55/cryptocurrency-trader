<?php
namespace App\Services;

use App\Models\UserRobotReference;

abstract class SignalAction
{
    public function exec(UserRobotReference $robotReference, string $coinCode) {}

    protected function checkExistedRunningRobot(int $userId, int $signalId, string $coinCode, string $baseCoinCode)
    {
        return $this->userRunningRobotModel
            ->where('user_id', '=', $userId)
            ->where('signal_id', '=', $signalId)
            ->where('coin_code', '=', $coinCode)
            ->where('base_coin_code', '=', $baseCoinCode)
            ->exists();
    }
}
