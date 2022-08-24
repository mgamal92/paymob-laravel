<?php

use App\Models\User;

return [

    /*
    |--------------------------------------------------------------------------
    | Messenger Default User Model
    |--------------------------------------------------------------------------
    |
    | This option defines the default User model.
    |
    */

    'user' => [
        'model' => User::class
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
        'api_key'    => env('PAYMOB_API_KEY'),
    ],
];