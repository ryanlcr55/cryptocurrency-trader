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

    public function exec(array $receivedSignal, SignalActionInterface $actionService)
    {
        $signal = $this->getSignal($receivedSignal['name']);
        if (!$signal || $signal->userRobotReference) {
            return;
        }

        $signal->userRobotReference->map(function ($reference) use ($actionService) {
            $actionService->exec($reference, $receivedSignal['coin']);
        });
    }

    public function getSignal($name)
    {
        return $this->signalModel
            ->where('name', '=', $name)
            ->with('userRobotReferences')
            ->first();
    }
}
