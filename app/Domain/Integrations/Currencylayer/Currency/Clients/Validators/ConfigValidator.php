<?php

namespace App\Domain\Integrations\Currencylayer\Currency\Clients\Validators;

use App\Domain\Integrations\Generic\Currency\Clients\Validators\ValidatorInterface;
use InvalidArgumentException;
use Webmozart\Assert\Assert;

class ConfigValidator implements ValidatorInterface
{
    public static function validate(array $data): void
    {
        Assert::notEmpty($data['host_url'] ?? '', 'Config param integrations.currencylayer.host_url must be set');
        if (filter_var($data['host_url'], FILTER_VALIDATE_URL) === false) {
            throw new InvalidArgumentException('Config param integrations.currencylayer.host_url must be a valid url');
        }
        Assert::notEmpty($data['access_key'] ?? '', 'Config param integrations.currencylayer.access_key must be set');
        Assert::notEmpty($data['default_currency'] ?? '', 'Config param currencies.default must be set');
    }
}
