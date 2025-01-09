<?php

namespace Tests\Unit\Domain\Integrations\Currencylayer\Clients\Validators;

use App\Domain\Integrations\Currencylayer\Currency\Clients\Validators\QuoteValidator;
use InvalidArgumentException;
use Tests\TestCase;

class QuoteValidatorTest extends TestCase
{
    /**
     * @dataProvider provideTestQuoteValidator
     */
    public function test_quote_validator(string $exceptionClass, string $exceptionMessage, array $quote)
    {
        $this->expectException($exceptionClass);
        $this->expectExceptionMessage($exceptionMessage);
        QuoteValidator::validate($quote);
    }

    public static function provideTestQuoteValidator(): array
    {
        return [
            [
                InvalidArgumentException::class,
                'Currency must be provided to QuoteValidator',
                [],
            ],
            [
                InvalidArgumentException::class,
                'Currency provided is not in a valid format: ABCD',
                [
                    'currencyIso' => 'ABCD',
                ],
            ],
            [
                InvalidArgumentException::class,
                'Exchange rate provided must be a numeric value',
                [
                    'currencyIso' => 'ABC',
                    'exchangeRate' => 'ABC',
                ],
            ],
        ];
    }
}
