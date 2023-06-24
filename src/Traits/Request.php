<?php

declare(strict_types=1);

namespace MG\Paymob\Traits;

use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

trait Request {

    private $base_url;

    public function __construct() {
        $this->base_url = config('paymob.base_url');
    }

    private function getBaseUrl(): string {
        return $this->base_url;
    }

    public function get(string $url, array $params = []) {
        return $this->request('get', $url, $params);
    }

    public function post(string $url, array $params = []) {
        return $this->request('post', $url, $params);
    }

    private function request(string $method, string $url, array $params): Response {
        try {
            $fullUrl = $this->base_url . $url;
            $response = Http::$method($fullUrl, $params);
            $this->checkNotSuccessfullyResponse($response);
            return $response;
        } catch (Exception $e) {
            throw $e;
        }
    }


    public function checkNotSuccessfullyResponse(Response $response) {
        if (!$response->successful()) {
            throw new Exception($response->getBody()->getContents(), $response->status());
        }
    }
}
