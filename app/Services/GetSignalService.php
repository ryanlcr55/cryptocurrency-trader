<?php
namespace App\Services;

use App\Models\Signal;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GetSignalService
{
    protected $signalModel;

    public function __construct(
        Signal $signalModel
        ) {
        $this->signalModel = $signalModel;
    }

    public function exec(string $name, SignalActionInterface $actionService)
    {
        $signal = $this->getSignal($name);
        if (!$signal || $signal->userRobotReference) {
            return;
        }

        $signal->userRobotReference->map(function ($reference) use ($actionService) {
            $actionService->exec($reference);
        });
    }

    public function getSignal($name)
    {
        return $this->signalModel
        ->where('name' , '=', $name)
        ->with('userRobotReference')
        ->first();
    }

    protected function newRobot($reference)
    {
        try {
            

        } catch (\Exception $e) {
            DB::rollBack();
            Log::Critical('Failed to create Robot, user_id: ' . $reference->user_id . ', msg:'. $e->getMessage());
        }
    }




    
}