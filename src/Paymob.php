<?php

declare(strict_types=1);

namespace MG\Paymob;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class Paymob
{
    public const URL = 'https://accept.paymob.com/api';
    /**
     * The Integration ID.
     *
     * @var string
     */
    protected $integrationId;

    /**
     * The Iframe ID.
     *
     * @var string
     */
    protected $iframeId;

    /**
     * Constructor.
     */
    public function __construct(string $integrationId = null, string $iframeId = null)
    {
        $this->integrationId = $integrationId ?: config('paymob.auth.integration_id');
        $this->iframeId = $iframeId ?: config('paymob.auth.iframe_id');
    }

    /**
     * Set The Integration ID.
     */
    public function setIntegrationId(string $integrationId): self
    {
        $this->integrationId = $integrationId;

        return $this;
    }

    /**
     * Set The Iframe ID.
     */
    public function setIframeId(string $iframeId): self
    {
        $this->iframeId = $iframeId;

        return $this;
    }

    /**
     * Paymob Authentication.
     */
    public function auth(): array
    {
        // Request body
        $json = [
            'api_key' => config('paymob.auth.api_key'),
        ];

        // Send curl
        $response =  Http::post(
            config('paymob.base_url').'/auth/tokens',
            $json
        );

        return $response->json();
    }

    /**
     * Send order to paymob servers.
     */
    public function makeOrder(string $token, bool $deliveryNeeded, int $amountCents, array $items, string $merchantOrderId): array
    {
        $json = [
            'auth_token' => $token,
            'delivery_needed' => $deliveryNeeded,
            'amount_cents' => $amountCents,
            'items' => $items,
            'merchant_order_id' => $merchantOrderId,
        ];

        $response = Http::post(
            config('paymob.base_url').'/ecommerce/orders',
            $json
        );

        return $response->json();
    }

    /**
     * Get Payment key to load iframe on paymob servers.
     */
    public function getPaymentKey(string $token, int $amountCents, int $expiration, int $orderId, array $billingData, string $currency): array
    {
        $json = [
            'auth_token' => $token,
            'amount_cents' => $amountCents,
            'expiration' => $expiration,
            'order_id' => $orderId,
            'billing_data' => $billingData,
            'currency' => $currency,
            'integration_id' => $this->integrationId,
        ];

        $response = Http::post(
            config('paymob.base_url').'/acceptance/payment_keys',
            $json
        );

        return $response->json();
    }

    /**
     * Make payment for API (moblie clients).
     * Return iframe_url.
     *
     * @param string $paymentToken
     */
    public function makePayment(array $data, $mobileWallet = null): string
    {
        // step 1 -> Authentication
        $authToken = $this->autheticate();

        // step 2 -> Order Registration
        $orderId = $this->registerOrder($authToken, $data);

        // step 3 => Get Payment Key
        $paymentToken = $this->createPaymentToken($authToken, $orderId, $data);

        if ($mobileWallet) {
            $walletResponse = $this->prepareWalletRedirectionUrl($paymentToken, $mobileWallet);

            return $walletResponse['redirect_url'];
        }

        // step 4 => build iframe url
        return $this->buildIframeUrl($paymentToken);
    }

    /**
     * Capture authed order.
     *
     * @param string $token
     * @param int    $transactionId
     * @param int  amount
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
     */
    public function getOrders($authToken, $page = 1): Response
    {
    }

    /**
     * Get paymob order.
     *
     * @param string $authToken
     * @param int    $orderId
     */
    public function getOrder($authToken, $orderId): Response
    {
    }

    /**
     * Get Paymob all transactions.
     *
     * @param string $authToken
     * @param string $page
     */
    public function getTransactions($authToken, $page = 1): Response
    {
    }

    /**
     * Get Paymob transaction.
     *
     * @param string $authToken
     * @param int    $transactionId
     */
    public function getTransaction($authToken, $transactionId): Response
    {
    }

    /**
     * authenticate request
     * return authToken.
     */
    private function autheticate(): string
    {
        $authResponse = $this->auth();
        $authToken = $authResponse['token'];

        return $authToken;
    }

    /**
     * register order request
     * return orderId.
     */
    private function registerOrder(string $authToken, array $data): string
    {
        $deliveryNeeded = $data['delivery_needed'] ?? false;
        $amountCents = $data['amount_cents'] ?? 0;
        $items = $data['items'] ?? [];
        $merchantOrderId = $data['merchant_order_id'] ?? null;

        $orderResponse = $this->makeOrder($authToken, $deliveryNeeded, $amountCents, $items, $merchantOrderId);

        return $orderResponse['id'];
    }

    /**
     * create payment token request
     * return paymentToken.
     */
    private function createPaymentToken(string $authToken, string $orderId, array $data): string
    {
        $amountCents = (isset($data['amount_cents']) && $data['amount_cents']) ? $data['amount_cents'] : 0;
        $expiration = (isset($data['expiration']) && $data['expiration']) ? $data['expiration'] : 3600;
        $merchantOrderId = (isset($data['merchant_order_id']) && $data['merchant_order_id']) ? $data['merchant_order_id'] : null;
        $billingData = (isset($data['billing_data']) && $data['billing_data']) ? $data['billing_data'] : [];
        $currency = (isset($data['currency']) && $data['currency']) ? $data['currency'] : 'EGP';

        $paymentKeyResponse = $this->getPaymentKey($authToken, $amountCents, $expiration, $orderId, $billingData, $currency);

        return $paymentKeyResponse['token'];
    }

    /**
     * build iframe url using payment token and iframe id
     * return iframeUrl.
     */
    private function buildIframeUrl(string $paymentToken): string
    {
        $iframeUrl = 'https://accept.paymobsolutions.com/api/acceptance/iframes/'.$this->iframeId.'?payment_token='.$paymentToken;

        return $iframeUrl;
    }

    /**
     * Send order to paymob servers.
     *
     * @param string $token
     * @param bool   $deliveryNeeded
     * @param int    $amountCents
     * @param array  $items
     *
     * @return array
     */
    public function prepareWalletRedirectionUrl(string $paymentToken, string $mobileWallet = null)
    {
        $json = [
            'payment_token' => $paymentToken,
            'source' => [
                'subtype' => 'WALLET',
                'identifier' => $mobileWallet,
            ],
        ];

        $response = Http::post(
            config('paymob.base_url').'/acceptance/payments/pay',
            $json
        );

        return $response->json();
    }
}
