<?php

namespace Tests\Unit\Domain\Currency\Calculator;

use App\Domain\Currency\Models\Currency;
use App\Domain\Currency\Services\Calculator\CurrencyExchangeCalculator;
use App\Domain\Currency\Services\Calculator\CurrencyExchangeResult;
use Tests\TestCase;

class CurrencyExchangeCalculatorTest extends TestCase
{
    /**
     * @dataProvider provideTestCalculationsData
     */
    public function testCalculations(array $input, array $expected)
    {
        $currencyExchangeCalculator = app(CurrencyExchangeCalculator::class);
        $currency = $input['currency']();
        /** @var CurrencyExchangeResult $currencyExchangeResult */
        $currencyExchangeResult = $currencyExchangeCalculator($currency, $input['purchased_amount']);
        $this->assertEquals($currency->exchange_rate, $currencyExchangeResult->exchange_rate);
        $this->assertEquals($expected['paid_amount'], $currencyExchangeResult->paid_amount);
        $this->assertEquals($currency->surcharge_percentage, $currencyExchangeResult->surcharge_percentage);
        $this->assertEquals($expected['surcharge_amount'], $currencyExchangeResult->surcharge_amount);
        $this->assertEquals($currency->discount_percentage, $currencyExchangeResult->discount_percentage);
        $this->assertEquals($expected['discount_amount'], $currencyExchangeResult->discount_amount);
    }

    public static function provideTestCalculationsData(): array
    {
        return [
            // Case 1: No surcharge or discount
            [
                [
                    'currency' => function () {
                        return Currency::factory()->make([
                            'exchange_rate' => 100,
                            'surcharge_percentage' => 0,
                            'discount_percentage' => 0,
                        ]);
                    },
                    'purchased_amount' => 10000,
                ],
                [
                    'paid_amount' => 100,
                    'surcharge_amount' => 0,
                    'discount_amount' => 0,
                ],
            ],
            // Case 2: With surcharge, no discount
            [
                [
                    'currency' => function () {
                        return Currency::factory()->make([
                            'exchange_rate' => 100,
                            'surcharge_percentage' => 5,
                            'discount_percentage' => 0,
                        ]);
                    },
                    'purchased_amount' => 10000,
                ],
                [
                    'paid_amount' => 105,
                    'surcharge_amount' => 5,
                    'discount_amount' => 0,
                ],
            ],
            // Case 3: No surcharge, with discount
            [
                [
                    'currency' => function () {
                        return Currency::factory()->make([
                            'exchange_rate' => 100,
                            'surcharge_percentage' => 0,
                            'discount_percentage' => 5,
                        ]);
                    },
                    'purchased_amount' => 10000,
                ],
                [
                    'paid_amount' => 95,
                    'surcharge_amount' => 0,
                    'discount_amount' => 5,
                ],
            ],
            // Case 4: With surcharge, with discount
            [
                [
                    'currency' => function () {
                        return Currency::factory()->make([
                            'exchange_rate' => 100,
                            'surcharge_percentage' => 5,
                            'discount_percentage' => 2,
                        ]);
                    },
                    'purchased_amount' => 10000,
                ],
                [
                    'paid_amount' => 102.9,
                    'surcharge_amount' => 5,
                    'discount_amount' => 2.1,
                ],
            ],
        ];
    }
}
