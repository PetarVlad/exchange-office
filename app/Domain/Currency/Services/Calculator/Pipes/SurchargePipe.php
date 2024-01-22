<?php

namespace App\Domain\Currency\Services\Calculator\Pipes;

use App\Domain\Currency\Services\Calculator\ExchangeResult;
use Closure;

class SurchargePipe
{
    public function handle(ExchangeResult $currencyExchangeResult, Closure $next): ExchangeResult
    {
        $currencyExchangeResult->surcharge_percentage = $currencyExchangeResult->currency->surcharge_percentage;
        $currencyExchangeResult->surcharge_amount = round($currencyExchangeResult->converted_amount * $currencyExchangeResult->surcharge_percentage / 100, 2);
        $currencyExchangeResult->paid_amount += $currencyExchangeResult->surcharge_amount;

        return $next($currencyExchangeResult);
    }
}
