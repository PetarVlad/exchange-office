<?php

use App\Http\Api\Controllers\CurrencyResourceController;
use App\Http\Api\Controllers\OrderResourceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::resource('orders', OrderResourceController::class)->only([
    'store',
]);

Route::resource('currencies', CurrencyResourceController::class)->only([
    'index',
]);
