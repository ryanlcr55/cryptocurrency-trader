<?php

namespace App\Services;

use App\Models\UserRobotReference;

class SellSignalService implements SignalActionInterface
{
    public function __construct(
        protected ShotDownRobotService $shotDownRobotService,
    ) {
    }

    public function exec(UserRobotReference $robotReference, string $coinCode)
    {
        $robotReference->runningRobot($coinCode)->each(function ($runningRobot) {
            $this->shotDownRobotService->exec($runningRobot);
        });
    }
}
