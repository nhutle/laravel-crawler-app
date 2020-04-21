<?php

namespace App;

use KubAT\PhpSimple\HtmlDomParser;
use Serps\Core\Browser\Browser;
use Serps\Exception;
use Serps\Exception\RequestError\RequestErrorException;
use Serps\HttpClient\CurlClient;
use Serps\SearchEngine\Google\GoogleClient;
use Serps\SearchEngine\Google\GoogleUrl;

class GoogleSerp
{
    /**
     * Crawler using cURL.
     * @param string $keyword
     * @return bool|mixed
     */
    public function cURLSpider(string $keyword) {
        $url = "http://www.google.com/search?hl=en&tbo=d&site=&source=hp&q=".urlencode($keyword);

        // Create a new cURL resource:
        $curl = curl_init();

        if (!$curl) {
            die("Couldn't initialize a cURL handle");
        }

        // Set the file URL to fetch through cURL:
        curl_setopt($curl, CURLOPT_URL, $url);

        /**
         * Possible user agent:
         * Googlebot/2.1 (+http://www.google.com/bot.html)
         * Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1
         * Opera/9.80 (J2ME/MIDP; Opera Mini/4.2.14912/870; U; id) Presto/2.4.15 (very old Opera Mobile browser)
         */
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
            // cURL executed successfully, let return DOM:
            return HtmlDomParser::str_get_html($html);
        }
    }

    /**
     * Crawler using Google Serps library.
     * @param $keyword
     * @return array|bool
     * @throws \Serps\Exception
     * @throws \Serps\SearchEngine\Google\Exception\InvalidDOMException
     */
    public function serpsSpider($keyword) {
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
            'keyword'              => $keyword,
            'total_adwords'        => 0,
            'total_links'          => 0,
            'total_search_results' => 0,
            'html_code'            => ''
        );

        try {
            $response        = $googleClient->query($googleUrl);
            $adWords         = $response->getAdwordsResults();
            $links           = $response->cssQuery('a');
            $numberOfResults = $response->getNumberOfResults();
            $DOM             = $response->getDom();

            // Collect data:
            $result['total_adwords']        = $adWords->count() ? : 0;
            $result['total_links']          = $links->count() ? : 0;
            $result['total_search_results'] = $numberOfResults ? : 0;
            $result['html_code']            = $DOM->saveHTML() ? : '';
        } catch (RequestErrorException $e) {
            throw new Exception($e->getMessage());
        }

        return $result;
    }
}