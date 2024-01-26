<?php

namespace App\Domain\Currency\Services\Calculator\Pipes;

use App\Domain\Currency\Services\Calculator\ExchangeResult;
use Closure;

class DiscountPipe implements PipeInterface
{
    public function handle(ExchangeResult $currencyExchangeResult, Closure $next): ExchangeResult
    {
        $currencyExchangeResult->discountPercentage = $currencyExchangeResult->currency->discount_percentage;
        $currencyExchangeResult->discountAmount = round($currencyExchangeResult->paidAmount * $currencyExchangeResult->discountPercentage / 100, 2);
        $currencyExchangeResult->paidAmount -= $currencyExchangeResult->discountAmount;

        return $next($currencyExchangeResult);
    }
}
