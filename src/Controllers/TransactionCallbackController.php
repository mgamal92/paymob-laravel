<?php

declare(strict_types=1);

namespace MG\Paymob\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionCallbackController extends Controller
{
    /**
     * Handle Transaction Callback Processed.
     *
     * @return void
     */
    public function processed(Request $request)
    {
    }

    /**
     * Handle Transaction Callback Response.
     *
     * @return void
     */
    public function response(Request $request)
    {
    }
}
