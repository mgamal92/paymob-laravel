<?php

namespace MG\Paymob\Traits;

use Illuminate\Support\Facades\Http;

trait WalletPayment
{
    /**
     * Send order to paymob servers.
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
            config('paymob.base_url').'/acceptance/payments/pay',
            $json
        );

        return $response->json();
    }
}