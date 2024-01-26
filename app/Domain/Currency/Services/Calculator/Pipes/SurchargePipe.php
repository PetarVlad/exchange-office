<?php

namespace App\Domain\Currency\Services\Calculator\Pipes;

use App\Domain\Currency\Services\Calculator\ExchangeResult;
use Closure;

class SurchargePipe implements PipeInterface
{
    public function handle(ExchangeResult $currencyExchangeResult, Closure $next): ExchangeResult
    {
        $currencyExchangeResult->surchargePercentage = $currencyExchangeResult->currency->surcharge_percentage;
        $currencyExchangeResult->surchargeAmount = round($currencyExchangeResult->convertedAmount * $currencyExchangeResult->surchargePercentage / 100, 2);
        $currencyExchangeResult->paidAmount += $currencyExchangeResult->surchargeAmount;

        return $next($currencyExchangeResult);
    }
}
