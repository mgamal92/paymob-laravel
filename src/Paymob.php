<?php

namespace MG\PayMob;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class Paymob
{
    const URL = 'https://accept.paymob.com/api';

    /**
     * Paymob Authentication
     *
     * @return array
     */
    public function auth(): array
    {
        // Request body
        $json = [
            'api_key' => config('paymob.auth.api_key'),
        ];

        // Send curl
        $response =  Http::post(
            self::URL.'/auth/tokens',
            $json
        );

        dd ($response->json());
    }

    /**
     * Send order to paymob servers
     *
     * @param string $token
     * @param bool $deliveryNeeded
     * @param int $amountCents
     * @param array $items
     * @return array
     */
    public function makeOrder(string $token, bool $deliveryNeeded, int $amountCents, array $items): array
    {
        $json = [
            'auth_token' => $token,
            'delivery_needed' => $deliveryNeeded,
            'amount_cents' => $amountCents,
            'items' => $items,
        ];

        $response = Http::post(
            self::URL.'/ecommerce/orders',
            $json
        );

        return $response->json();
    }

    /**
     * Get Payment key to load iframe on paymob servers
     *
     * @param string $token
     * @param int $amountCents
     * @param int $expiration
     * @param int $orderId
     * @param array $billingData
     * @param string $currency
     * @return array
     */
    public function getPaymentKey(string $token, int $amountCents, int $expiration, int $orderId, array $billingData, string $currency): array
    {
        $integrationId = config('paymob.auth.integration_id');
        
        $json = [
            'auth_token' => $token,
            'amount_cents' => $amountCents,
            'expiration' => $expiration,
            'order_id' => $orderId,
            'billing_data' => $billingData,
            'currency' => $currency,
            'integration_id' => $integrationId
        ];

        $response = Http::post(
            self::URL.'/acceptance/payment_keys',
            $json
        );

        dd($response->json());
    }

    /**
     * Make payment for API (moblie clients).
     *
     * @param string $paymentToken
     * @return string
     */
    public function makePayment(string $paymentToken): string
    {
        $iframeId = config('paymob.auth.iframe_id');
        $response = Http::get(
            'https://accept.paymobsolutions.com/api/acceptance/iframes/'. $iframeId .'?payment_token='.$paymentToken,
        );

        return $response->body();
    }

    /**
     * Capture authed order.
     *
     * @param string $token
     * @param int $transactionId
     * @param int  amount
     * @return array
     */
    public function capture($token, $transactionId, $amount): array
    {
        return [];
    }

    /**
     * Get paymob all orders.
     *
     * @param string $authToken
     * @param string $page
     * @return Response
     */
    public function getOrders($authToken, $page = 1): Response
    {
    }

    /**
     * Get paymob order.
     *
     * @param  string  $authToken
     * @param  int  $orderId
     * @return Response
     */
    public function getOrder($authToken, $orderId): Response
    {

    }

    /**
     * Get Paymob all transactions.
     *
     * @param  string  $authToken
     * @param  string  $page
     * @return Response
     */
    public function getTransactions($authToken, $page = 1): Response
    {

    }

    /**
     * Get Paymob transaction.
     *
     * @param  string  $authToken
     * @param  int  $transactionId
     * @return Response
     */
    public function getTransaction($authToken, $transactionId): Response
    {

    }
}