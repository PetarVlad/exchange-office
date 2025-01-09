<?php

namespace Tests\Feature\Http\Api\Controllers;

use App\Models\Currency;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class OrderResourceControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $endpointBase = '/api/orders';

    public function testStoreNoBodyRequest()
    {
        $response = $this->postJson($this->endpointBase);
        $response->assertInvalid([
            'currency_iso' => 'The currency iso field is required.',
            'purchased_amount' => 'The purchased amount field is required.',
        ]);
    }

    /**
     * @dataProvider provideTestValidationsData
     */
    public function testStoreValidations($body, $errors)
    {
        Currency::factory()->create([
            'iso' => 'XYZ',
        ]);
        $response = $this->postJson($this->endpointBase, $body);

        $response->assertInvalid($errors);
    }

    public static function provideTestValidationsData(): array
    {
        return [
            //Case 1: Currency iso parameter is not a string
            [
                [
                    'currency_iso' => 123,
                    'purchased_amount' => 123.45,
                ],
                [
                    'currency_iso' => 'The currency iso field must only contain letters.',
                ],
            ],
            //Case 2: Currency iso parameter is not 3 characters
            [
                [
                    'currency_iso' => 'ABCD',
                    'purchased_amount' => 123.45,
                ],
                [
                    'currency_iso' => 'The currency iso field must be 3 characters.',
                ],
            ],
            //Case 3: Currency does not exist
            [
                [
                    'currency_iso' => 'ABC',
                    'purchased_amount' => 123.45,
                ],
                [
                    'currency_iso' => 'The selected currency iso is invalid.',
                ],
            ],
            //Case 4: Purchased amount is not a numeric
            [
                [
                    'currency_iso' => 'XYZ',
                    'purchased_amount' => 'ABC',
                ],
                [
                    'purchased_amount' => [
                        'The purchased amount field must be a number.',
                    ],
                ],
            ],
            //Case 5: Purchased amount is less than 0
            [
                [
                    'currency_iso' => 'XYZ',
                    'purchased_amount' => -1,
                ],
                [
                    'purchased_amount' => [
                        'The purchased amount field must be greater than 0.',
                    ],
                ],
            ],
            //Case 6: Purchased amount has more than 6 whole digits
            [
                [
                    'currency_iso' => 'XYZ',
                    'purchased_amount' => 1000000,
                ],
                [
                    'purchased_amount' => [
                        'The purchased amount must be a valid numeric value with up to two decimal places.',
                    ],
                ],
            ],
            //Case 7: Purchased amount has more than 2 decimals
            [
                [
                    'currency_iso' => 'XYZ',
                    'purchased_amount' => 123.456,
                ],
                [
                    'purchased_amount' => [
                        'The purchased amount must be a valid numeric value with up to two decimal places.',
                    ],
                ],
            ],
        ];
    }

    public function testStoreOrderSuccessful()
    {
        Currency::factory()->create([
            'iso' => 'XYZ',
        ]);
        $response = $this->postJson($this->endpointBase, [
            'currency_iso' => 'XYZ',
            'purchased_amount' => 123.45,
        ]);

        $response->assertStatus(201)
            ->assertJson(fn (AssertableJson $json) => $json->has('data.id')
                ->missing('message')
            );
        $responseContent = $response->json();
        $this->assertDatabaseHas('orders', [
            'id' => $responseContent['data']['id'],
        ]);
    }
}
