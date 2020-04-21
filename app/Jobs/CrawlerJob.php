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

    private $keyword;

    /**
     * Create a new job instance.
     * @param string $keyword
     * @return void
     */
    public function __construct(string $keyword)
    {
        $this->keyword = $keyword;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $keyword  = trim($this->keyword);
        echo '==> Crawling google with keyword='.$keyword.PHP_EOL;
        $googleSerp = new GoogleSerp();

        try {
            $response = $googleSerp->serpsSpider($keyword);

            // Save statistic:
            Statistic::create($response);

            // Sleep a bit:
            sleep(rand(1, 3));
        } catch (\Exception $e) {
            echo $e->getMessage().PHP_EOL;
        }
    }
}
