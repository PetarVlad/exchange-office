<?php

namespace Tests\Feature\Http\Api\Controllers;

use Tests\TestCase;

class CurrencyResourceControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->getJson('/api/currencies');
        $response->assertStatus(200);
    }
}
