<?php

namespace App\Http\Controllers;

use App\Http\Requests\Signal\ReceivedSignalRequest;
use App\Services\BuySignalService;
use App\Services\GetSignalService;
use App\Services\SellSignalService;
use Illuminate\Routing\Controller as BaseController;

class SignalController extends BaseController
{
    public function receivedSignal(ReceivedSignalRequest $request, GetSignalService $getSignalService)
    {
        $attribute = $request->all();
        $actionService = match($attribute['action']) {
            'buy' => resolve(BuySignalService::class),
            'sell' => resolve(SellSignalService::class),
        };

        $getSignalService->exec($attribute['name'], $attribute['coin'], $actionService);
    }
}
