<?php

namespace Tests\Unit\Domain\Currency\Calculator;

use App\Domain\Currency\Services\Calculator\ExchangeCalculator;
use App\Domain\Currency\Services\Calculator\Pipes\DiscountPipe;
use App\Domain\Currency\Services\Calculator\Pipes\ExchangePipe;
use App\Domain\Currency\Services\Calculator\Pipes\SurchargePipe;
use App\Models\Currency;
use Illuminate\Pipeline\Pipeline;
use Tests\TestCase;

class ExchangeCalculatorTest extends TestCase
{
    /**
     * @dataProvider provideTestCalculationsData
     */
    public function testCalculations(array $input, array $expected)
    {
        $currencyExchangeCalculator = new ExchangeCalculator(
            [
                ExchangePipe::class,
                SurchargePipe::class,
                DiscountPipe::class,
            ],
            $this->app->make(Pipeline::class)
        );
        $currency = $input['currency']();
        $currencyExchangeResult = $currencyExchangeCalculator($currency, $input['purchased_amount']);
        $this->assertEquals($expected['purchased_amount'], $currencyExchangeResult->purchasedAmount);
        $this->assertEquals($expected['exchange_rate'], $currencyExchangeResult->exchangeRate);
        $this->assertEquals($expected['paid_amount'], $currencyExchangeResult->paidAmount);
        $this->assertEquals($expected['surcharge_percentage'], $currencyExchangeResult->surchargePercentage);
        $this->assertEquals($expected['surcharge_amount'], $currencyExchangeResult->surchargeAmount);
        $this->assertEquals($expected['discount_percentage'], $currencyExchangeResult->discountPercentage);
        $this->assertEquals($expected['discount_amount'], $currencyExchangeResult->discountAmount);
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
                    'purchased_amount' => 10000,
                    'exchange_rate' => 100,
                    'paid_amount' => 100,
                    'surcharge_percentage' => 0,
                    'surcharge_amount' => 0,
                    'discount_percentage' => 0,
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
                    'purchased_amount' => 10000,
                    'exchange_rate' => 100,
                    'paid_amount' => 105,
                    'surcharge_percentage' => 5,
                    'surcharge_amount' => 5,
                    'discount_percentage' => 0,
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
                    'purchased_amount' => 10000,
                    'exchange_rate' => 100,
                    'paid_amount' => 95,
                    'surcharge_percentage' => 0,
                    'surcharge_amount' => 0,
                    'discount_percentage' => 5,
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
                    'purchased_amount' => 10000,
                    'exchange_rate' => 100,
                    'paid_amount' => 102.9,
                    'surcharge_percentage' => 5,
                    'surcharge_amount' => 5,
                    'discount_percentage' => 2,
                    'discount_amount' => 2.1,
                ],
            ],
        ];
    }
}
