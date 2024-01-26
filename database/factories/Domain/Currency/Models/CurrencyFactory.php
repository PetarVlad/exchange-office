<?php

namespace Database\Factories\Domain\Currency\Models;

use Database\Factories\Factory;

class CurrencyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'iso' => $this->faker->unique()->currencyCode(),
            'exchange_rate' => $this->faker->randomFloat(6, 0, 10000),
            'surcharge_percentage' => $this->faker->randomFloat(2, 0, 100),
            'discount_percentage' => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}
