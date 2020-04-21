<?php

namespace App\Jobs;

use App\GoogleSerp;
use App\Models\Statistic;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CrawlerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $keywords;

    /**
     * Create a new job instance.
     * @param array $keywords
     * @return void
     */
    public function __construct(array $keywords)
    {
        $this->keywords = $keywords;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        echo 'Crawling google...'.PHP_EOL;
        $googleSerp = new GoogleSerp();

        foreach ($this->keywords as $keyword) {
            $keyword  = trim($keyword);

            try {
                $response = $googleSerp->serpsSpider($keyword);

                // Save statistic:
                Statistic::create($response);

                // Sleep a bit:
                sleep(rand(3, 5));
            } catch (\Exception $e) {
                echo $e->getMessage().PHP_EOL;
            }
        }
    }
}
