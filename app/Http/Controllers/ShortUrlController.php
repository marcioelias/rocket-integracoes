<?php

namespace App\Http\Controllers;

use App\ShortUrlConfig;
use ArieTimmerman\Laravel\URLShortener\URLShortener;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ShortUrlController extends Controller
{
    private $shortUrlConfig;

    public function __construct()
    {
        $this->shortUrlConfig = ShortUrlConfig::first();
    }

    public function shortUrl(string $url) {
        switch ($this->shortUrlConfig->short_url_api) {
            case 'local':
                return $this->shortLocal($url);
                break;

            case 'short_cm':
                return $this->shortCmApiCall($url);
                break;
        }
    }

    private function getUrlWithEndSlash(string $url) {
        $splited_url = str_split($url);
        return end($splited_url) == '/' ? $url : $url.'/';
    }

    private function getUrlWithoutEndSlash(string $url) {
        $splited_url = str_split($url);
        return end($splited_url) == '/' ? rtrim($url, "/") : $url;
    }

    private function shortLocal(string $url) {
        try {
            $domain = $this->getUrlWithEndSlash($this->shortUrlConfig->short_domain);
            $localShortedUrl = explode('/', (string)URLShortener::shorten($url));
            return $domain.end($localShortedUrl);
        } catch (\Exception $e) {
            Log::emergency($e);
            return $url;
        }
    }

    private function shortCmApiCall(string $url) {
        try {
            $base_url = 'https://api.short.cm/links';
            $response = Http::asForm()
                ->withHeaders([
                    'authorization' => $this->shortUrlConfig->api_key,
                    'content-type' => 'application/json'
                ])
                ->post($base_url, [
                    'originalURL' => $url,
                    'domain' => $this->getUrlWithoutEndSlash($this->shortUrlConfig->short_domain)
                ]);

            $arrResp = json_decode($response, true);
            if (isset($arrResp['secureShortURL']) && $arrResp['secureShortURL']) {
                return $arrResp['secureShortURL'];
            } else if (isset($arrResp['shortURL']) && $arrResp['shortURL']) {
                return json_decode($response, true)['shortURL'];
            } else {
                return $url;
            }
        } catch (\Exception $e) {
            Log::emergency($e);
            return $url;
        }

    }
}
