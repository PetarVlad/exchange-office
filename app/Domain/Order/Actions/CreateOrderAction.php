<?php

namespace App\Domain\Order\Actions;

use App\Domain\Currency\Services\Calculator\ExchangeCalculator;
use App\Domain\Order\DataTransferObjects\OrderRequestDto;
use App\Models\Currency;
use App\Models\Order;

class CreateOrderAction
{
    public function __construct(public ExchangeCalculator $exchangeCalculator) {}

    public function __invoke(OrderRequestDto $orderRequestDto): Order
    {
        $currency = Currency::where('iso', $orderRequestDto->currencyIso)->first();
        $currencyExchangeResult = ($this->exchangeCalculator)($currency, $orderRequestDto->purchasedAmount);

        return Order::create([
            'currency_id' => $currency->id,
            'purchased_amount' => $currencyExchangeResult->purchasedAmount,
            'exchange_rate' => $currencyExchangeResult->exchangeRate,
            'paid_amount' => $currencyExchangeResult->paidAmount,
            'surcharge_percentage' => $currencyExchangeResult->surchargePercentage,
            'surcharge_amount' => $currencyExchangeResult->surchargeAmount,
            'discount_percentage' => $currencyExchangeResult->discountPercentage,
            'discount_amount' => $currencyExchangeResult->discountAmount,
        ]);
    }
}
