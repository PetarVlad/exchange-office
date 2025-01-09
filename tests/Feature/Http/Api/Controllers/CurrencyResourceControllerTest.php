<?php

namespace Tests\Feature\Http\Api\Controllers;

use App\Models\Currency;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CurrencyResourceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        /** @var Currency[] $currencies */
        $currencies = Currency::factory()->count(3)->create();
        $response = $this->getJson('/api/currencies');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
        foreach ($currencies as $currency) {
            $response->assertJsonFragment([
                [
                    'id' => $currency->id,
                    'iso' => $currency->iso,
                    'exchange_rate' => $currency->exchange_rate,
                    'surcharge_percentage' => $currency->surcharge_percentage,
                    'discount_percentage' => $currency->discount_percentage,
                ],
            ]);
        }
    }
}
