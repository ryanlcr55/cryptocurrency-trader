<?php

namespace App\Console\Commands;

use App\Models\Signal;
use Illuminate\Console\Command;

class CreateSignal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:createSignal {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command create signal';

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
        try {
            $model::create([
                'name' => $this->argument('name'),
            ]);
            $this->info('ok');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }

        return 0;
    }
}
