<?php

namespace App\Domain\Order\Actions;

use App\Domain\Currency\Models\Currency;
use App\Domain\Currency\Services\Calculator\ExchangeCalculator;
use App\Domain\Order\DataTransferObjects\OrderRequestDto;
use App\Domain\Order\Models\Order;

class CreateOrderAction
{
    public function __construct(public ExchangeCalculator $exchangeCalculator){
    }

    public function __invoke(OrderRequestDto $orderRequestDto): Order
    {
        $currency = Currency::where('iso', $orderRequestDto->currency_iso)->first();
        $currencyExchangeResult = $this->exchangeCalculator($currency, $orderRequestDto->purchased_amount);

        return Order::create([
            'currency_id' => $currency->id,
            'purchased_amount' => $currencyExchangeResult->purchased_amount,
            'exchange_rate' => $currencyExchangeResult->exchange_rate,
            'paid_amount' => $currencyExchangeResult->paid_amount,
            'surcharge_percentage' => $currencyExchangeResult->surcharge_percentage,
            'surcharge_amount' => $currencyExchangeResult->surcharge_amount,
            'discount_percentage' => $currencyExchangeResult->discount_percentage,
            'discount_amount' => $currencyExchangeResult->discount_amount,
        ]);
    }

}
