<?php

namespace MG\Paymob\Controllers;

use App\Http\Controllers\Controller;
use MG\Paymob\Paymob;

class PaymobController extends Controller
{
    private $payMob = null;
    
    public function __construct(Paymob $payMob)
    {
        $this->payMob = $payMob;
    }

    /**
     * @TODO will be removed!
     */
    public function test()
    {
        $billingData = [
            "apartment" => "NA",
            "email" => "test@test.com",
            "floor" => "NA",
            "first_name" => "test",
            "street" => "NA",
            "building" => "NA",
            "phone_number" => "01234567890",
            "shipping_method" => "NA",
            "postal_code" => "NA",
            "city" => "NA",
            "country" => "NA",
            "last_name" => "NA",
            "state" => "NA"
        ];

        $data = [
            'delivery_needed' => false, // default false
            'amount_cents' => 10000, // default 0
            'items' => [], //default []
            'expiration' => 3600, // default 3600
            'merchant_order_id' => '123', // default null
            'billing_data' => $billingData, // required
            'currency' => 'EGP', // default EGP
        ];

        // $token = 'ZXlKMGVYQWlPaUpLVjFRaUxDSmhiR2NpT2lKSVV6VXhNaUo5LmV5SmpiR0Z6Y3lJNklrMWxjbU5vWVc1MElpd2ljR2hoYzJnaU9pSTBPR0V4WVRJeU1tSmtORGswWkRSallUZGpNVFkzWXprd01qZzROV1U0T1RRM016UXhaamRoWWpBME5HUTBNakE1WkdKbFkySXdNR0kwTlRJeVlqazRJaXdpY0hKdlptbHNaVjl3YXlJNk16Y3lOREV4TENKbGVIQWlPakUyTmpFME56UXpPRGg5LlNoV1ltWkc3UkpQOHdXelQycEkxaUdoVU92S1RCSUZ0M2hIdmU1cU5qeXV0b2JQcHo5ZUZNdHR4Ql9hWERfb0VKX2NLNVU1Um5JU0U4N2x0N2IzWi1B';

        $iframeUrl = $this->payMob->makePayment($data);
        
        return redirect()->to($iframeUrl);
    }
}