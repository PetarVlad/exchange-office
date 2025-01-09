<?php

namespace Tests\Unit\Domain\Integrations\Currencylayer\Services;

use App\Domain\Integrations\Currencylayer\Currency\Exceptions\ClientException;
use App\Domain\Integrations\Currencylayer\Currency\Exceptions\UpdateServiceException;
use App\Domain\Integrations\Currencylayer\Currency\Services\UpdateService;
use App\Domain\Integrations\Generic\Currency\Clients\ClientInterface;
use App\Domain\Integrations\Generic\Currency\DataTransferObjects\QuoteDto;
use App\Models\Currency;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_client_exception()
    {
        $this->mock(ClientInterface::class)->shouldReceive('getAllExisting')->andThrow(new ClientException('Test exception'));
        $updateService = app(UpdateService::class);
        $this->expectException(UpdateServiceException::class);
        $this->expectExceptionMessage('Client error: Test exception');
        $updateService->updateAll();
    }

    public function test_update_service_exception()
    {
        $this->mock(ClientInterface::class)->shouldReceive('getAllExisting')->andThrow(new UpdateServiceException('Test exception'));
        $updateService = app(UpdateService::class);
        $this->expectException(UpdateServiceException::class);
        $this->expectExceptionMessage('Test exception');
        $updateService->updateAll();
    }

    public function test_update(): void
    {
        /** @var Currency[] $currencies */
        $currencies = Currency::factory()->count(5)->create();
        $expected = $currencies->map(function ($currency) {
            return [
                'iso' => $currency->iso,
                'exchange_rate' => $this->faker->randomFloat(6, 0, 10000),
            ];
        })->toArray();
        $orderDtoArray = [];
        foreach ($expected as $currency) {
            $orderDtoArray[] = new QuoteDto(
                $currency['iso'],
                $currency['exchange_rate']
            );
        }
        $quoteDtoCollection = collect($orderDtoArray);
        $this->mock(ClientInterface::class)->shouldReceive('getAllExisting')->andReturn($quoteDtoCollection);
        $updateService = app(UpdateService::class);
        $updateService->updateAll();
        foreach ($expected as $currency) {
            $this->assertDatabaseHas('currencies', $currency);
        }
    }
}
