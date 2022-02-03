<?php

namespace App\Console\Commands;

use App\Models\Signal;
use Illuminate\Console\Command;

class GetSignals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:getSignals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Signal $model)
    {
        $signals = $model::get();
        $this->line('Signal list:');
        $signals->each(function ($signal) {
            echo 'id: ' . $signal->id . ', name: ' . $signal->name . PHP_EOL;
        });


        return 0;
    }
}
