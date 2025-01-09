<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        Currency::factory()->create([
            'iso' => 'JPY',
            'exchange_rate' => 107.17,
            'surcharge_percentage' => 7.5,
            'discount_percentage' => 0,
        ]);

        Currency::factory()->create([
            'iso' => 'GBP',
            'exchange_rate' => 0.711178,
            'surcharge_percentage' => 5,
            'discount_percentage' => 0,
        ]);

        Currency::factory()->create([
            'iso' => 'EUR',
            'exchange_rate' => 0.884872,
            'surcharge_percentage' => 5,
            'discount_percentage' => 2,
        ]);
    }
}
