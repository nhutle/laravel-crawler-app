<?php

namespace App\Jobs;

use App\Models\Statistic;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use KubAT\PhpSimple\HtmlDomParser;
use Serps\Exception\RequestError\RequestErrorException;
use Serps\SearchEngine\Google\GoogleClient;
use Serps\HttpClient\CurlClient;
use Serps\SearchEngine\Google\GoogleUrl;
use Serps\Core\Browser\Browser;

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

        foreach ($this->keywords as $keyword) {
            $keyword  = trim($keyword);
            $response = $this->serpSpider($keyword);

            // Save statistic:
            Statistic::create($response);

            // Sleep a bit:
            sleep(rand(3, 5));
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

    private function serpSpider($keyword) {
        $userAgent       = "Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.93 Safari/537.36";
        $browserLanguage = "en-EN";

        // Create a browser:
        $browser = new Browser(new CurlClient(), $userAgent, $browserLanguage);

        // Create a google client using the above browser:
        $googleClient = new GoogleClient($browser);

        // Create the url that will be parsed:
        $googleUrl = new GoogleUrl();
        $googleUrl->setSearchTerm($keyword);

        $result = array(
            'keyword'       => $keyword,
            'total_adwords' => 0,
            'total_links'   => 0,
            'total_search_results' => 0,
            'html_code' => ''
        );

        try {
            $response        = $googleClient->query($googleUrl);
            $adwords         = $response->getAdwordsResults();
            $links           = $response->cssQuery('a');
            $numberOfResults = $response->getNumberOfResults();
            $DOM             = $response->getDom();

            // Collect data:
            $result['total_adwords']        = $adwords->count() ? : 0;
            $result['total_links']          = $links->count() ? : 0;
            $result['total_search_results'] = $numberOfResults ? : 0;
            $result['html_code']            = $DOM->saveHTML() ? : '';
        } catch (RequestErrorException $e) { // Error on network connection
            // Some error with the request:
            // todo throw exception
            $errorInfo = $e->getMessage();
            return false;
        }

        return $result;
    }
}
