<?php

namespace App\Domain\Currency\Services\Calculator\Pipes;

use App\Domain\Currency\Services\Calculator\CurrencyExchangeResult;
use Closure;

class ExchangePipe
{
    public function handle(CurrencyExchangeResult $currencyExchangeResult, Closure $next): CurrencyExchangeResult
    {
        $currencyExchangeResult->exchange_rate = $currencyExchangeResult->currency->exchange_rate;
        $currencyExchangeResult->converted_amount = round($currencyExchangeResult->purchased_amount / $currencyExchangeResult->exchange_rate, 2);
        $currencyExchangeResult->paid_amount = $currencyExchangeResult->converted_amount;

        return $next($currencyExchangeResult);
    }
}
