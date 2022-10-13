<?php

use Illuminate\Support\Facades\Route;
use MG\Paymob\Controllers\PaymobController;
use MG\Paymob\Controllers\TransactionCallbackController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('transaction/callback/processed', [config('paymob.controller', TransactionCallbackController::class), 'processed']);
Route::get('transaction/callback/response', [config('paymob.controller', TransactionCallbackController::class), 'response']);