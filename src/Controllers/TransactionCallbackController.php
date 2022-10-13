<?php

namespace MG\Paymob\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MG\Paymob\Paymob;

abstract class TransactionCallbackController extends Controller
{
    /**
     * Handle Transaction Callback Processed
     * @param Request $request
     * @return void
     */
    public abstract function processed(Request $request);

    /**
     * Handle Transaction Callback Response
     * @param Request $request
     * @return void
     */
    public abstract function response(Request $request);
}
