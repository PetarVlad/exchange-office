<?php

namespace App\Domain\Currency\Services\Calculator\Pipes;

use App\Domain\Currency\Services\Calculator\CurrencyExchangeResult;
use Closure;

class DiscountPipe
{
    public function handle(CurrencyExchangeResult $currencyExchangeResult, Closure $next): CurrencyExchangeResult
    {
        $currencyExchangeResult->discount_percentage = $currencyExchangeResult->currency->discount_percentage;
        $currencyExchangeResult->discount_amount = round($currencyExchangeResult->paid_amount * $currencyExchangeResult->discount_percentage / 100, 2);
        $currencyExchangeResult->paid_amount -= $currencyExchangeResult->discount_amount;

        return $next($currencyExchangeResult);
    }
}
