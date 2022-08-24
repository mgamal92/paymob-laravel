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
        return $this->payMob->makeOrder();
    }
}