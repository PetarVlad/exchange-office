<?php

namespace App\Domain\Integrations\Currencylayer\Currency\Clients\Validators;

use App\Domain\Integrations\Generic\Currency\Clients\Validators\ValidatorInterface;
use InvalidArgumentException;
use Webmozart\Assert\Assert;

class QuoteValidator implements ValidatorInterface
{
    public static function validate(array $data): void
    {
        Assert::notEmpty($data['currencyIso'] ?? null, 'Currency must be provided to QuoteValidator');
        if (! preg_match('/^[A-Z]{3}$/', $data['currencyIso'])) {
            throw new InvalidArgumentException('Currency provided is not in a valid format: '.$data['currencyIso']);
        }
        Assert::float($data['exchangeRate'] ?? null, 'Exchange rate provided must be a numeric value');
    }
}
