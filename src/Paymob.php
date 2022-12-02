<?php

namespace MG\Paymob;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class Paymob
{
    /**
     * The Integration ID
     *
     * @var String
     */
    protected $integrationId;

    /**
     * The Iframe ID
     *
     * @var String
     */
    protected $iframeId;

    /**
     * Constructor
     *
     * @param string|null $integrationId
     * @param string|null $iframeId
     */
    public function __construct(string $integrationId = null, string $iframeId = null)
    {
        $this->integrationId = $integrationId ?: config('paymob.auth.integration_id');
        $this->iframeId = $iframeId ?: config('paymob.auth.iframe_id');
    }

    /**
     * Set The Integration ID
     *
     * @param string $integrationId
     * @return self
     */
    public function setIntegrationId(string $integrationId): self
    {
        $this->integrationId = $integrationId;

        return $this;
    }

    /**
     * Set The Iframe ID
     *
     * @param string $iframeId
     * @return self
     */
    public function setIframeId(string $iframeId): self
    {
        $this->iframeId = $iframeId;

        return $this;
    }

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
            config('paymob.base_url').'/auth/tokens',
            $json
        );

        return $response->json();
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
        $json = [
            'auth_token' => $token,
            'amount_cents' => $amountCents,
            'expiration' => $expiration,
            'order_id' => $orderId,
            'billing_data' => $billingData,
            'currency' => $currency,
            'integration_id' => $this->integrationId
        ];

        $response = Http::post(
            config('paymob.base_url').'/acceptance/payment_keys',
            $json
        );

        return $response->json();
    }

    /**
     * Make payment for API (moblie clients).
     * Return iframe_url
     *
     * @param string $paymentToken
     * @return string
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

    /**
     * authenticate request
     * return authToken
     *
     * @return string
     */
    private function autheticate(): string
    {
        $authResponse = $this->auth();
        $authToken = $authResponse['token'];
        return $authToken;
    }

    /**
     * register order request
     * return orderId
     *
     * @param string $authToken
     * @param array $data
     * @return string
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
     * return paymentToken
     *
     * @param string $authToken
     * @param string $orderId
     * @param array $data
     * @return string
     */
    private function createPaymentToken(string $authToken, string $orderId, array $data): string
    {
        $amountCents = (isset($data['amount_cents']) && $data['amount_cents'])  ? $data['amount_cents'] : 0;
        $expiration = (isset($data['expiration']) && $data['expiration'])  ? $data['expiration'] : 3600;
        $merchantOrderId = (isset($data['merchant_order_id']) && $data['merchant_order_id'])  ? $data['merchant_order_id'] : null;
        $billingData = (isset($data['billing_data']) && $data['billing_data'])  ? $data['billing_data'] : [];
        $currency = (isset($data['currency']) && $data['currency'])  ? $data['currency'] : 'EGP';

        $paymentKeyResponse = $this->getPaymentKey($authToken, $amountCents, $expiration, $orderId, $billingData, $currency);

        return $paymentKeyResponse['token'];
    }


    /**
     * build iframe url using payment token and iframe id
     * return iframeUrl
     *
     * @param string $paymentToken
     * @return string
     */
    private function buildIframeUrl(string $paymentToken): string
    {
        $iframeUrl = 'https://accept.paymobsolutions.com/api/acceptance/iframes/'. $this->iframeId .'?payment_token='.$paymentToken;
        return $iframeUrl;
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
