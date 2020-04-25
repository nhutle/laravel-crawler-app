<?php

namespace App\Console\Commands;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SoftDeleteTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'softDeleteTasks:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Soft delete tasks that success within past 7 days';

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
     * @return mixed
     */
    public function handle()
    {
        Task::where('status', 'success')
            ->delete();
    }
}
