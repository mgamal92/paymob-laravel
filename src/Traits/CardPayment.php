<?php

declare(strict_types=1);

namespace MG\Paymob\Traits;

trait CardPayment
{
    /**
     * build iframe url using payment token and iframe id.
     */
    private function buildIframeUrl(string $paymentToken): string
    {
        return 'https://accept.paymobsolutions.com/api/acceptance/iframes/'.config('paymob.auth.iframe_id').'?payment_token='.$paymentToken;
    }
}
