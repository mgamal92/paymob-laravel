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
     * @param Request $request
     * @return void
     */
    public function processed(Request $request): void
    {
    }

    /**
     * Handle Transaction Callback Response.
     *
     * @param Request $request
     * @return void
     */
    public function response(Request $request): void
    {
    }
}
