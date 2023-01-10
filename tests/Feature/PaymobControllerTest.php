<?php

namespace Tests\Feature;

use MG\Paymob\Paymob;
use Tests\TestCase;

/**
 * This is just an example test class to make sure the test environment works as expected.
 */
class PaymobControllerTest extends TestCase
{
    /**
     * @test
     */
    public function test_getting_iframe_id(): void
    {
        $billingData = [
            "apartment" => "NA",
            "email" => "test2@test2.com",
            "floor" => "NA",
            "first_name" => "test1",
            "street" => "NA",
            "building" => "NA",
            "phone_number" => "01234127890",
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
            'merchant_order_id' => rand(1,2000), // default null
            'billing_data' => $billingData, // required
            'currency' => 'EGP', // default EGP
        ];


        $this->assertStringContainsString('https://accept.paymobsolutions.com/api/acceptance/iframes/',
            (new Paymob())->makePayment($data));
    }
}
