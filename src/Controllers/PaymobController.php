<?php

namespace MG\Paymob\Controllers;

use App\Http\Controllers\Controller;
use MG\Paymob\Paymob;

class PaymobController extends Controller
{
    public function __construct(private Paymob $payMob)
    {
    }

    /**
     * @TODO will be removed!
     */
    public function test()
    {
        $token = 'ZXlKMGVYQWlPaUpLVjFRaUxDSmhiR2NpT2lKSVV6VXhNaUo5LmV5SmpiR0Z6Y3lJNklrMWxjbU5vWVc1MElpd2ljR2hoYzJnaU9pSTBPR0V4WVRJeU1tSmtORGswWkRSallUZGpNVFkzWXprd01qZzROV1U0T1RRM016UXhaamRoWWpBME5HUTBNakE1WkdKbFkySXdNR0kwTlRJeVlqazRJaXdpY0hKdlptbHNaVjl3YXlJNk16Y3lOREV4TENKbGVIQWlPakUyTmpFME56UXpPRGg5LlNoV1ltWkc3UkpQOHdXelQycEkxaUdoVU92S1RCSUZ0M2hIdmU1cU5qeXV0b2JQcHo5ZUZNdHR4Ql9hWERfb0VKX2NLNVU1Um5JU0U4N2x0N2IzWi1B';

        return $this->payMob->makeOrder($token, false, 11122, []);
    }
}