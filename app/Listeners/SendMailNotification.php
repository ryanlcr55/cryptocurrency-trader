<?php

namespace App\Listeners;

use App\Events\TradeTriggered;
use App\Mail\TradeTriggered as TradeTriggeredMail;
use Illuminate\Support\Facades\Mail;

class SendMailNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\TradeTriggered  $event
     * @return void
     */
    public function handle(TradeTriggered $event)
    {
        $record = $event->orderRecord;

        Mail::to($record->user->email)->send(new TradeTriggeredMail($record));
    }
}
