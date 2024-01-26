<?php

namespace App\Domain\Currency\Services\Calculator\Pipes;

use App\Domain\Currency\Services\Calculator\ExchangeResult;
use Closure;

class ExchangePipe implements PipeInterface
{
    public function handle(ExchangeResult $currencyExchangeResult, Closure $next): ExchangeResult
    {
        $currencyExchangeResult->exchangeRate = $currencyExchangeResult->currency->exchange_rate;
        $currencyExchangeResult->convertedAmount = round($currencyExchangeResult->purchasedAmount / $currencyExchangeResult->exchangeRate, 2);
        $currencyExchangeResult->paidAmount = $currencyExchangeResult->convertedAmount;

        return $next($currencyExchangeResult);
    }
}
