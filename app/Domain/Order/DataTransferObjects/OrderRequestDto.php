<?php

namespace App\Domain\Order\DataTransferObjects;

class OrderRequestDto
{
    public function __construct(
        public string $currency_iso,
        public float $purchased_amount
    ) {
    }
}
