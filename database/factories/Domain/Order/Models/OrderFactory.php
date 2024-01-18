<?php

namespace Database\Factories\Domain\Order\Models;

use App\Domain\Currency\Models\Currency;
use App\Domain\Order\Models\Order;
use Database\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'currency_id' => Currency::factory(),
            'purchased_amount' => $this->faker->randomFloat(2, 0, 10000),
            'exchange_rate' => $this->faker->randomFloat(2, 0, 10000),
            'paid_amount' => $this->faker->randomFloat(2, 0, 10000),
            'surcharge_percentage' => $this->faker->randomFloat(2, 0, 100),
            'surcharge_amount' => $this->faker->randomFloat(2, 0, 10000),
            'discount_percentage' => $this->faker->randomFloat(2, 0, 100),
            'discount_amount' => $this->faker->randomFloat(2, 0, 10000),
        ];
    }

    //TODO: Implement currency exchange calc
    public function calculated()
    {
        return $this->state([])->afterMaking(function (Order $order) {
            $baseAmountPaid = round($order->purchased_amount / $order->currency->exchange_rate, 2);
            $order->exchange_rate = $order->currency->exchange_rate;
            $order->surcharge_percentage = $order->currency->surcharge_percentage;
            $order->surcharge_amount = round($baseAmountPaid * $order->currency->surcharge_percentage / 100, 2);
            $order->discount_percentage = $order->currency->discount_percentage;
            $order->discount_amount = round($baseAmountPaid * $order->currency->discount_percentage / 100, 2);
            $order->paid_amount = $baseAmountPaid + $order->surcharge_amount - $order->discount_amount;
        });
    }
}
