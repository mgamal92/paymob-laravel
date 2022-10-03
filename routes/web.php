<?php

use Illuminate\Support\Facades\Route;
use MG\Paymob\Controllers\PaymobController;

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

Route::get('paymob-test', [PaymobController::class, 'test']);

Route::get('test', function(){
    $rows = Spatie\SimpleExcel\SimpleExcelReader::create('../addresses.csv')->getRows();
    dump($rows->isEmpty());
    dump($rows->count());
    dump($rows->isEmpty());
});