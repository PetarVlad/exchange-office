<?php

namespace App\Domain\Integrations\Generic\Currency\DataTransferObjects;

class QuoteDto
{
    public function __construct(
        public string $currencyIso,
        public float $exchangeRate
    ) {
    }
}
