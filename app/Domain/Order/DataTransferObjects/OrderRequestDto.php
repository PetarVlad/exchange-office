<?php

namespace App\Domain\Order\DataTransferObjects;

class OrderRequestDto
{
    public function __construct(
        public string $currencyIso,
        public float $purchasedAmount
    ) {
    }
}
