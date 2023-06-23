<?php

declare(strict_types=1);

namespace MG\Paymob\Traits;

use MG\Paymob\Traits\Request;
use Illuminate\Support\Facades\Http;

class WalletPayment extends Request
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
