<?php

namespace App\Domain\Currency\Services\Calculator\Pipes;

use App\Domain\Currency\Services\Calculator\ExchangeResult;
use Closure;

interface PipeInterface
{
    public function handle(ExchangeResult $currencyExchangeResult, Closure $next): ExchangeResult;
}
