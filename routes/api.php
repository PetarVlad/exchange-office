<?php

use App\Http\Api\Controllers\CurrencyResourceController;
use App\Http\Api\Controllers\OrderResourceController;
use Illuminate\Support\Facades\Route;

Route::resource('orders', OrderResourceController::class)->only([
    'store',
]);

Route::resource('currencies', CurrencyResourceController::class)->only([
    'index',
]);
