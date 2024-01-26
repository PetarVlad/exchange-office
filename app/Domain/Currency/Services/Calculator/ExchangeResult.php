<?php

namespace App\Domain\Currency\Services\Calculator;

use App\Domain\Currency\Models\Currency;

class ExchangeResult
{
    public function __construct(
        public Currency $currency,
        public float $purchasedAmount,
        public float $exchangeRate = 0,
        public float $paidAmount = 0,
        public float $surchargePercentage = 0,
        public float $surchargeAmount = 0,
        public float $discountPercentage = 0,
        public float $discountAmount = 0,
        public float $convertedAmount = 0
    ) {

    }
}
