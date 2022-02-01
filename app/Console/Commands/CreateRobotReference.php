<?php

namespace App\Console\Commands;

use App\Models\UserRobotReference;
use Illuminate\Console\Command;

class CreateRobotReference extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:createRobotReference {user_id} {signal_id} {unit_percent} {limit_percent} {stop_percent}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command robot reference';

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
    public function handle(UserRobotReference $robotReferenceModel)
    {
        try {
            $robotReferenceModel::create(
                [
                    'user_id' => $this->argument('user_id'),
                    'signal_id' => $this->argument('signal_id'),
                    'unit_percent' => $this->argument('unit_percent'),
                    'limit_percent' => $this->argument('limit_percent'),
                    'stop_percent' => $this->argument('stop_percent'),
                ]
            );
            $this->info('ok');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        return 0;
    }
}
