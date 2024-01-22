<?php

namespace Tests\Unit\Domain\Integrations\Currencylayer\Clients;

use App\Domain\Currency\Models\Currency;
use App\Domain\Integrations\Currencylayer\Currency\Clients\Client;
use App\Domain\Integrations\Currencylayer\Currency\Exceptions\ClientException;
use App\Domain\Integrations\Generic\Currency\DataTransferObjects\QuoteDto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private array $config;

    public function setUp(): void
    {
        parent::setUp();
        $this->config = [
            'default_currency' => 'XYZ',
            'host_url' => 'http://localhost/',
            'access_key' => 'ABCD1234'
        ];
    }
    /**
     * @dataProvider provideTestConfigData
     */
    public function testConfig(string $exceptionClass, $exceptionMessage, $config){
        $this->expectException($exceptionClass);
        $this->expectExceptionMessage($exceptionMessage);
        new Client($config);
    }

    public static function provideTestConfigData(): array
    {
        return [
            [
                InvalidArgumentException::class,
                'Config param integrations.currencylayer.host_url must be set',
                []
            ],
            [
                InvalidArgumentException::class,
                'Config param integrations.currencylayer.host_url must be a valid url',
                ['host_url' => 'ABC']
            ],
            [
                InvalidArgumentException::class,
                'Config param integrations.currencylayer.access_key must be set',
                ['host_url' => 'http://localhost/']
            ],
            [
                InvalidArgumentException::class,
                'Config param currencies.default must be set',
                ['host_url' => 'http://localhost/', 'access_key' => 'ABCD1234']
            ],
        ];
    }

    public function testErrorResponse(){
        Http::fake([
            $this->config['host_url'].'live*' => Http::response(null, 500)
        ]);
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage('HTTP request returned status code 500');
        $client = new Client($this->config);
        $client->getAll();
    }

    /**
     * @dataProvider provideMalformedResponseData
     */
    public function testMalformedResponse(array $response){
        Http::fake([
            $this->config['host_url'].'live*' => Http::response($response)
        ]);
        $this->expectException(ClientException::class);
        $this->expectExceptionMessage('Malformed response object recieved');
        $client = new Client($this->config);
        $client->getAll();
    }

    public static function provideMalformedResponseData(): array
    {
        return [
            [
                []
            ],
            [
                [
                    'success' => false
                ]
            ],
            [
                [
                    'success' => true
                ]
            ],
            [
                [
                    'success' => true,
                    'quotes' => []
                ]
            ],
        ];
    }

    public function testSuccessResponseGetAll(){
        $defaultCurrency = $this->config['default_currency'];
        $expected = [
            "ABC" => 1.234567,
            "BCD" => 2.345678,
            "DEF" => 3.456789,
            "EFG" => 4.567890,
            "GHK" => 5.678901
        ];
        $quotes = [];
        foreach($expected as $currencyIso => $exchangeRate){
            $quotes[$defaultCurrency.$currencyIso] = $exchangeRate;
        }
        Http::fake([
            $this->config['host_url'].'live*' => Http::response([
                  "success" => true,
                  "terms" => "https://currencylayer.com/terms",
                  "privacy" => "https://currencylayer.com/privacy",
                  "timestamp" => time(),
                  "source" => $defaultCurrency,
                  "quotes" => $quotes
            ])
        ]);
        $client = new Client($this->config);
        $collection = $client->getAll();
        $this->assertCount(count($expected), $collection);
        $collection->each(function($currency) use ($expected){
            $this->assertInstanceOf(QuoteDto::class, $currency);
            $this->assertEquals($expected[$currency->currency_iso], $currency->exchange_rate);
        });
    }

    public function testSuccessResponseGetAllExisting(){
        $defaultCurrency = $this->config['default_currency'];
        $currencies = Currency::factory()->count(5)->create();
        $expected = $currencies->pluck('exchange_rate', 'iso')->toArray();
        $quotes = [];
        foreach($expected as $currencyIso => $exchangeRate){
            $expected[$currencyIso] = $this->faker->randomFloat(6, 0, 10000);
            $quotes[$defaultCurrency.$currencyIso] = $expected[$currencyIso];
        }
        Http::fake([
            $this->config['host_url'].'live*' => Http::response([
                "success" => true,
                "terms" => "https://currencylayer.com/terms",
                "privacy" => "https://currencylayer.com/privacy",
                "timestamp" => time(),
                "source" => $defaultCurrency,
                "quotes" => $quotes
            ])
        ]);
        $client = new Client($this->config);
        $collection = $client->getAllExisting();
        $this->assertCount(5, $collection);
        $collection->each(function($currency) use ($expected){
            $this->assertInstanceOf(QuoteDto::class, $currency);
            $this->assertEquals($expected[$currency->currency_iso], $currency->exchange_rate);
        });
    }

}
