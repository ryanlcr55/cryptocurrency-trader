<?php

namespace App\Console\Commands;

use App\Exchange\ExchangeBinance;
use App\Models\UserRunningRobot;
use App\Services\ShutDownRobotService;
use Illuminate\Console\Command;

class RunRobot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:runRobot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var UserRunningRobot
     */
    protected $runningRobotModel;

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
    public function handle(
        UserRunningRobot     $runningRobotModel,
        ShutDownRobotService $shotDownRobotService
    ) {
        $this->runningRobotModel = $runningRobotModel;
        $this->getRunningRobot()->each(function ($runningRobot) use ($shotDownRobotService) {
            $user = $runningRobot->user;
            $exchange = new ExchangeBinance($user->exchange_api_key, $user->exchange_secret_key);
            $price = (float) $exchange->getPrice($runningRobot->coin_code . $runningRobot->base_coin_code);
            if ($price >= $runningRobot->upper_limit_price || $price <= $runningRobot->lowwer_limit_price) {
                $shotDownRobotService->exec($runningRobot);
            }
        });

        return 0;
    }

    protected function getRunningRobot()
    {
        return $this->runningRobotModel::query()
            ->where('status', '=', UserRunningRobot::STATUS_ACTIVED)
            ->get();
    }
}
