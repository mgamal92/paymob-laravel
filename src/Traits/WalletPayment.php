<?php

declare(strict_types=1);

namespace MG\Paymob\Traits;

use MG\Paymob\Traits\Request;
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

        return  $this->post(
            '/acceptance/payments/pay',
            $json
        );
    }
}
