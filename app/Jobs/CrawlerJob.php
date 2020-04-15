<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use KubAT\PhpSimple\HtmlDomParser;

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
        foreach ($this->keywords as $keyword) {
            $html = $this->googleCrawler($keyword[0]);

            if ($html) {
                file_put_contents('sample2  .html', $html);
                //$totalResults = $html->find('#result-stats', 0);
                //var_dump($html);
                die();
                echo $totalResults->plaintext;
                sleep(1);
            } else {
                // fail
            }
        }
    }

    private function googleCrawler(string $keyword) {
        //$url = 'https://www.google.com/search?q='.implode('+', explode(' ', $keyword));
        $url = "http://www.google.com/search?hl=en&tbo=d&site=&source=hp&q=".urlencode($keyword);

        // Create a new cURL resource:
        $curl = curl_init();

        if (!$curl) {
            die("Couldn't initialize a cURL handle");
        }

        // Set the file URL to fetch through cURL:
        curl_setopt($curl, CURLOPT_URL, $url);

        // Set a different user agent string (Googlebot)
        //curl_setopt($curl, CURLOPT_USERAGENT, 'Googlebot/2.1 (+http://www.google.com/bot.html)');
        //curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1");
        curl_setopt($curl, CURLOPT_USERAGENT, 'Opera/9.80 (J2ME/MIDP; Opera Mini/4.2.14912/870; U; id) Presto/2.4.15');

        // Follow redirects, if any:
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

        // Fail the cURL request if response code = 400 (like 404 errors):
        curl_setopt($curl, CURLOPT_FAILONERROR, true);

        // Return the actual result of the curl result instead of success code:
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // Wait for 10 seconds to connect, set 0 to wait indefinitely:
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);

        // Execute the cURL request for a maximum of 50 seconds:
        curl_setopt($curl, CURLOPT_TIMEOUT, 50);

        // Do not check the SSL certificates:
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        // Fetch the URL and save the content in $html variable:
        $html = curl_exec($curl);

        // Check for possible errors:
        $error = curl_error($curl);

        // Get information regarding a specific transfer:
        $curl_info = curl_getinfo($curl);

        // Close cURL resource to free up system resources:
        curl_close($curl);

        // Check if any error has occurred:
        if ($error) {
            echo 'cURL error: ' . $error;
            return false;
        } else {
            // cURL executed successfully:
            //print_r($curl_info);

            // Return DOM:
            return HtmlDomParser::str_get_html($html);
        }
    }
}
