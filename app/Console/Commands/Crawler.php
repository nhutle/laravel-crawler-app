<?php

namespace App\Console\Commands;

use App\GoogleSerp;
use App\Models\Statistic;
use App\Models\Task;
use Illuminate\Console\Command;

class Crawler extends Command
{
    protected $maxAttempts;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to run crawler job';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->maxAttempts = 3;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Retrieve tasks:
        $tasks = Task::where('status', 'pending')
                ->orWhere(function($query)
                {
                    $query->where('status', 'failed')
                          ->where('attempts', '<', 3);
                })
            ->take(100)->get();

        // Loop over tasks:
        foreach ($tasks as $task) {
            $googleSerp = new GoogleSerp();

            try {
                $response = $googleSerp->serpsSpider($task->keyword);

                // Save statistic:
                Statistic::create($response);

                // Update task status:
                $task->update([
                    'status'   => 'success',
                    'attempts' => $task->attempts + 1
                ]);

                // Sleep a bit so that Google won't smell us hopefully:
                sleep(rand(1, 3));
            } catch (\Exception $e) {
                // Update task status:
                $task->update([
                    'status'    => 'failed',
                    'attempts'  => $task->attempts + 1,
                    'exception' => $e->getMessage()
                ]);
            }
        }
    }
}
