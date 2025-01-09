<?php

namespace Tests\Unit\Domain\Integrations\Currencylayer\Clients;

use App\Domain\Integrations\Currencylayer\Currency\Clients\Client;
use App\Domain\Integrations\Currencylayer\Currency\Clients\Mappers\QuoteDtoMapper;
use App\Domain\Integrations\Currencylayer\Currency\Exceptions\ClientException;
use App\Domain\Integrations\Generic\Currency\DataTransferObjects\QuoteDto;
use App\Models\Currency;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private array $config;

    protected function setUp(): void
    {
        parent::setUp();
        $this->config = [
            'default_currency' => 'XYZ',
            'host_url' => 'http://localhost/',
            'access_key' => 'ABCD1234',
        ];
    }

    public function test_error_response()
    {
        Http::fake([
            $this->config['host_url'].'live*' => Http::response(null, 500),
        ]);
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage('HTTP request returned status code 500');
        $client = new Client(
            $this->config,
            new QuoteDtoMapper($this->config['default_currency'])
        );
        $client->getAll();
    }

    public function test_success_response_get_all()
    {
        $defaultCurrency = $this->config['default_currency'];
        $expected = [
            'ABC' => 1.234567,
            'BCD' => 2.345678,
            'DEF' => 3.456789,
            'EFG' => 4.567890,
            'GHK' => 5.678901,
        ];
        $quotes = [];
        foreach ($expected as $currencyIso => $exchangeRate) {
            $quotes[$defaultCurrency.$currencyIso] = $exchangeRate;
        }
        Http::fake([
            $this->config['host_url'].'live*' => Http::response([
                'success' => true,
                'terms' => 'https://currencylayer.com/terms',
                'privacy' => 'https://currencylayer.com/privacy',
                'timestamp' => time(),
                'source' => $defaultCurrency,
                'quotes' => $quotes,
            ]),
        ]);
        $client = new Client($this->config, new QuoteDtoMapper($defaultCurrency));
        $collection = $client->getAll();
        $this->assertCount(count($expected), $collection);
        $collection->each(function ($currency) use ($expected) {
            $this->assertInstanceOf(QuoteDto::class, $currency);
            $this->assertEquals($expected[$currency->currencyIso], $currency->exchangeRate);
        });
    }

    public function test_success_response_get_all_existing()
    {
        $defaultCurrency = $this->config['default_currency'];
        $currencies = Currency::factory()->count(5)->create();
        $expected = $currencies->pluck('exchange_rate', 'iso')->toArray();
        $quotes = [];
        foreach ($expected as $currencyIso => $exchangeRate) {
            $expected[$currencyIso] = $this->faker->randomFloat(6, 0, 10000);
            $quotes[$defaultCurrency.$currencyIso] = $expected[$currencyIso];
        }
        Http::fake([
            $this->config['host_url'].'live*' => Http::response([
                'success' => true,
                'terms' => 'https://currencylayer.com/terms',
                'privacy' => 'https://currencylayer.com/privacy',
                'timestamp' => time(),
                'source' => $defaultCurrency,
                'quotes' => $quotes,
            ]),
        ]);
        $client = new Client($this->config, new QuoteDtoMapper($defaultCurrency));
        $collection = $client->getAllExisting();
        $this->assertCount(5, $collection);
        $collection->each(function ($currency) use ($expected) {
            $this->assertInstanceOf(QuoteDto::class, $currency);
            $this->assertEquals($expected[$currency->currencyIso], $currency->exchangeRate);
        });
    }
}
