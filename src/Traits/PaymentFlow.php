<?php

namespace MG\Paymob\Traits;

use Illuminate\Support\Facades\Http;

trait PaymentFlow
{
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
            'integration_id' => config('paymob.auth.integration_id'),
        ];

        $response = Http::post(
            config('paymob.base_url').'/acceptance/payment_keys',
            $json
        );

        return $response->json();
    }


    /**
     * authenticate request.
     */
    private function authenticate(): string
    {
        $authResponse = $this->auth();

        return $authResponse['token'];
    }

    /**
     * register order request.
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
     * Make payment for API (mobile clients).
     */
    public function makePayment(array $data, $mobileWallet = null): string
    {
        // step 1 -> Authentication
        $authToken = $this->authenticate();

        // step 2 -> Order Registration
        $orderId = $this->registerOrder($authToken, $data);

        // step 3 => Get Payment Key
        $paymentToken = $this->createPaymentToken($authToken, $orderId, $data);

        // @TODO: should be refactored!
        if ($mobileWallet) {
            $walletResponse = $this->prepareWalletRedirectionUrl($paymentToken, $mobileWallet);

            return $walletResponse['redirect_url'];
        }

        // step 4 => build iframe url
        return $this->buildIframeUrl($paymentToken);
    }



    /**
     * create payment token request.
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
}