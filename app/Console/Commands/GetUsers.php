<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class GetUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:getUsers';

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
    public function handle(User $model)
    {
        $users = $model::get();
        $this->line('User list:');
        $users->each(function ($user) {
            echo 'id: ' . $user->id . ', name: ' . $user->name . ', username: ' . $user->username . PHP_EOL;
        });


        return 0;
    }
}
