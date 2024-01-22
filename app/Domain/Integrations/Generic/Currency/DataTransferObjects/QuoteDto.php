<?php

namespace App\Domain\Integrations\Generic\Currency\DataTransferObjects;

//TODO: Add validation
class QuoteDto
{
    public function __construct(
        public string $currency_iso,
        public float $exchange_rate
    ) {
    }
}
