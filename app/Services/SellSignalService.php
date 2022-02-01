<?php

namespace App\Services;

use App\Models\UserRobotReference;
use App\Models\UserRunningRobot;

class SellSignalService implements SignalActionInterface
{
    public function __construct(
        protected ShotDownRobotService $shotDownRobotService,
        protected UserRunningRobot $userRunningRobotModel,
    ) {
    }

    public function exec(UserRobotReference $robotReference, string $coinCode)
    {
        $runningRobot = $this->getReferenceRunningRobot($robotReference, $coinCode);
        if ($runningRobot) {
            $this->shotDownRobotService->exec($runningRobot);
        }
    }

    public function getReferenceRunningRobot(UserRobotReference $robotReference, string $coinCode)
    {
        return $this->userRunningRobotModel
            ->where('user_id', $robotReference->user_id)
            ->where('signal_id', $robotReference->signal_id)
            ->where('status', UserRunningRobot::STATUS_ACTIVED)
            ->where('coin_code', $coinCode)
            ->first();
    }
}
