<?php

declare(strict_types=1);

namespace MG\Paymob\Traits;

use Exception;
use Illuminate\Support\Facades\Http;

trait Request
{
    private $base_url;

    public function __construct()
    {
        $this->base_url = config('paymob.base_url');
    }

    private function getBaseUrl()
    {
        return $this->base_url;
    }

    public function get($url, $params = []) : Returntype
    {
        return $this->request('get', $url, $params);
    }

    public function post($url, $params = []) : Returntype
    {
        return $this->request('post', $url, $params);
    }


    private function request($method, $url, $params)
    {
        try {
            return Http::$method($this->base_url.$url, $params)->json();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
