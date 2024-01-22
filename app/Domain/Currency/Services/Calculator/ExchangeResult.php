<?php

namespace App\Domain\Currency\Services\Calculator;

use App\Domain\Currency\Models\Currency;

class ExchangeResult
{
    public function __construct(
        public Currency $currency,
        public float $purchased_amount,
        public float $exchange_rate = 0,
        public float $paid_amount = 0,
        public float $surcharge_percentage = 0,
        public float $surcharge_amount = 0,
        public float $discount_percentage = 0,
        public float $discount_amount = 0,
        public float $converted_amount = 0
    ) {

    }
}
