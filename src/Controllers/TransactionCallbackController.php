<?php

declare(strict_types=1);

namespace MG\Paymob\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionCallbackController extends Controller
{
    /**
     * Handle Transaction Callback Processed.
     */
    public function processed(Request $request): void
    {
    }

    /**
     * Handle Transaction Callback Response.
     */
    public function response(Request $request): void
    {
    }
}
