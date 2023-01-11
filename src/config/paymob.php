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

//    PAYMOB_API_KEY=""
//
//    PAYMOB_HMAC_SECRET="DC9BE2D8E7B795453BB35AAD1D836A6D"
//
//    PAYMOB_CARD_INTEGRATION_ID="2651704"
//    PAYMOB_CARD_IFRAME_ID="455520"
//
//    PAYMOB_WALLET_INTEGRATION_ID="2912279"
//    PAYMOB_WALLET_IFRAME_ID="455521"

    'auth' => [
        'api_key' => env('PAYMOB_API_KEY', 'ZXlKaGJHY2lPaUpJVXpVeE1pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SmpiR0Z6Y3lJNklrMWxjbU5vWVc1MElpd2ljSEp2Wm1sc1pWOXdheUk2TXpjeU5ERXhMQ0p1WVcxbElqb2lhVzVwZEdsaGJDSjkuTHpoYXhMUThMWmprV2V2TmJmXzRRWXpMazBXcUx3elFrR0J0d1dqaTg0dWcyR0kxYm13bGJVcl9yb2h3NEo2OWR4OUZEdXNCQm9ONjRETlBPOTJUbkE='),
        'integration_id' => env('PAYMOB_INTEGRATION_ID', '2651704'),
        'iframe_id' => env('PAYMOB_IFRAME_ID', '455520'),
        'hmac_secret' => env('PAYMOB_HMAC_SECRET'),
    ],
];
