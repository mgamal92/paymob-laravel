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
        'username'    => env('PAYMOB_USERNAME'),
        'password'    => env('PAYMOB_PASSWORD'),
    ],
];