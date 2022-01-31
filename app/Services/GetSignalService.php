<?php

namespace App\Services;

use App\Models\Signal;

class GetSignalService
{
    protected $signalModel;

    public function __construct(
        Signal $signalModel
    ) {
        $this->signalModel = $signalModel;
    }

    public function exec(string $signalName, string $coin, SignalActionInterface $actionService)
    {
        $signal = $this->getSignal($signalName);
        if (!$signal || $signal->userRobotReference) {
            return;
        }

        $signal->userRobotReference->map(function ($reference) use ($actionService, $coin) {
            $actionService->exec($reference, $coin);
        });
    }

    public function getSignal($signalName)
    {
        return $this->signalModel
            ->where('name', '=', $signalName)
            ->with('userRobotReferences')
            ->first();
    }
}
