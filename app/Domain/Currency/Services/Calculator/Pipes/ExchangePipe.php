<?php

namespace App\Domain\Currency\Services\Calculator\Pipes;

use App\Domain\Currency\Services\Calculator\ExchangeResult;
use Closure;

class ExchangePipe
{
    public function handle(ExchangeResult $currencyExchangeResult, Closure $next): ExchangeResult
    {
        $currencyExchangeResult->exchange_rate = $currencyExchangeResult->currency->exchange_rate;
        $currencyExchangeResult->converted_amount = round($currencyExchangeResult->purchased_amount / $currencyExchangeResult->exchange_rate, 2);
        $currencyExchangeResult->paid_amount = $currencyExchangeResult->converted_amount;

        return $next($currencyExchangeResult);
    }
}
