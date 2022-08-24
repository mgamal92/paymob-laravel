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
            'api_key' => config('paymob.auth.api_token'),
        ];

        // Send curl
        $response =  Http::post(
            self::URL.'/auth/tokens',
            $json
        );

        return $response->json();
    }

    /**
     * Send order to paymob servers
     *
     * @param string $token
     * @param int $merchant_id
     * @param int $amount_cents
     * @param int $merchant_order_id
     * @return array
     */
    public function makeOrder(): array
    {
        return [];
    }

    /**
     * Get Payment key to load iframe on paymob servers
     *
     * @param string $token
     * @param int $amount_cents
     * @param int $order_id
     * @param string $email
     * @param string $fname
     * @param string $lname
     * @param int $phone
     * @param string $city
     * @param string $country
     * @return array
     */
    public function getPaymentKey(): array
    {
        return [];
    }

    /**
     * Make payment for API (moblie clients).
     *
     * @param string $token
     * @param int $card_number
     * @param string $card_holdername
     * @param int $card_expiry_mm
     * @param int $card_expiry_yy
     * @param int $card_cvn
     * @param int $order_id
     * @param string $firstname
     * @param string $lastname
     * @param string $email
     * @param string $phone
     * @return array
     */
    public function makePayment(): array
    {
        return [];
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