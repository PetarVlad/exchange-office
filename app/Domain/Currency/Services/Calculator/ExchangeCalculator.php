<?php

namespace App\Domain\Currency\Services\Calculator;

use App\Domain\Currency\Models\Currency;
use Illuminate\Pipeline\Pipeline;

class ExchangeCalculator
{
    public function __construct(private readonly array $pipes, private readonly Pipeline $pipeline)
    {
    }

    public function __invoke(Currency $currency, float $purchased_amount): ExchangeResult
    {
        $currencyExchangeResult = new ExchangeResult(currency: $currency, purchasedAmount: $purchased_amount);

        return $this->pipeline->send($currencyExchangeResult)
            ->through($this->pipes)
            ->thenReturn();
    }
}
