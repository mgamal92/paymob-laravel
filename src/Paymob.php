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
    protected mixed $integrationId;

    /**
     * The Iframe ID.
     *
     * @var string
     */
    protected mixed $iframeId;

    /**
     * Constructor.
     */
    public function __construct(string $integrationId = null, string $iframeId = null)
    {
        $this->integrationId = $integrationId ?: config('paymob.auth.integration_id');
        $this->iframeId = $iframeId ?: config('paymob.auth.iframe_id');
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
        $response = Http::post(
            self::URL.'/auth/tokens',
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
            self::URL.'/ecommerce/orders',
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
            self::URL.'/acceptance/payment_keys',
            $json
        );

        return $response->json();
    }

    /**
     * Make payment for API (mobile clients).
     * Return iframe_url.
     *
     * @param array $data
     * @param null $mobileWallet
     * @return string
     */
    public function makePayment(array $data, $mobileWallet = null): string
    {
        // step 1 -> Authentication
        $authToken = $this->authenticate();

        // step 2 -> Order Registration
        $orderId = $this->registerOrder($authToken, $data);

        // step 3 => Get Payment Key
        $paymentToken = $this->createPaymentToken($authToken, $orderId, $data);

        //@TODO: should be refactored!
        if ($mobileWallet) {
            $walletResponse = $this->prepareWalletRedirectionUrl($paymentToken, $mobileWallet);

            return $walletResponse['redirect_url'];
        }

        // step 4 => build iframe url
        return $this->buildIframeUrl($paymentToken);
    }

    /**
     * authenticate request
     * return authToken.
     */
    private function authenticate(): string
    {
        $authResponse = $this->auth();
        return $authResponse['token'];
    }

    /**
     * register order request
     * return orderId.
     */
    private function registerOrder(string $authToken, array $data): int
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
    private function createPaymentToken(string $authToken, int $orderId, array $data): string
    {
        $amountCents = (isset($data['amount_cents']) && $data['amount_cents']) ? $data['amount_cents'] : 0;
        $expiration = (isset($data['expiration']) && $data['expiration']) ? $data['expiration'] : 3600;
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
     * @param string $paymentToken
     * @param string|null $mobileWallet
     * @return array
     */
    public function prepareWalletRedirectionUrl(string $paymentToken, string $mobileWallet = null): array
    {
        $json = [
            'payment_token' => $paymentToken,
            'source' => [
                'subtype' => 'WALLET',
                'identifier' => $mobileWallet,
            ],
        ];

        $response = Http::post(
            self::URL.'/acceptance/payments/pay',
            $json
        );

        return $response->json();
    }
}
