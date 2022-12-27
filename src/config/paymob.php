<?php

declare(strict_types=1);

use App\Models\User;
use MG\Paymob\Controllers\TransactionCallbackController;

return [
    /*
    |--------------------------------------------------------------------------
    | Messenger Default User Model
    |--------------------------------------------------------------------------
    |
    | This option defines the default User model.
    |
    */

    'base_url' => 'https://accept.paymob.com/api',

    'controller' => TransactionCallbackController::class,

    'user' => [
        'model' => User::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Paymob Credentials
    |--------------------------------------------------------------------------
    |
    | Username and Password
    |
    */
    'auth' => [
        'api_key' => env('PAYMOB_API_KEY'),
        'integration_id' => env('PAYMOB_INTEGRATION_ID'),
        'iframe_id' => env('PAYMOB_IFRAME_ID'),
        'hmac_secret' => env('PAYMOB_HMAC_SECRET'),
    ],
];
