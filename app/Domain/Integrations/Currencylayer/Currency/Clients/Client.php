<?php

namespace App\Domain\Integrations\Currencylayer\Currency\Clients;

use App\Domain\Currency\Models\Currency;
use App\Domain\Integrations\Currencylayer\Currency\Exceptions\ClientException;
use App\Domain\Integrations\Generic\Currency\Clients\ClientInterface;
use App\Domain\Integrations\Generic\Currency\DataTransferObjects\QuoteDto;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use Webmozart\Assert\Assert;

class Client implements ClientInterface
{
    private string $hostUrl;

    private string $accessKey;

    private string $defaultCurrency;

    public function __construct(
        array $config
    ) {
        $this->validateConfig($config);
        $this->hostUrl = $config['host_url'];
        $this->accessKey = $config['access_key'];
        $this->defaultCurrency = $config['default_currency'];
    }

    private function validateConfig(array $config): void
    {
        Assert::notEmpty($config['host_url'] ?? '', 'Config param integrations.currencylayer.host_url must be set');
        if (filter_var($config['host_url'], FILTER_VALIDATE_URL) === false) {
            throw new InvalidArgumentException('Config param integrations.currencylayer.host_url must be a valid url');
        }
        Assert::notEmpty($config['access_key'] ?? '', 'Config param integrations.currencylayer.access_key must be set');
        Assert::notEmpty($config['default_currency'] ?? '', 'Config param currencies.default must be set');
    }

    /**
     * @throws ClientException
     */
    public function getAll(array $params = []): Collection
    {
        try {
            $response = Http::get($this->hostUrl.'live', [
                'access_key' => $this->accessKey,
                'source' => $this->defaultCurrency,
                ...$params,
            ]);

            $response->throw();
        } catch (Exception $exception) {
            throw new ClientException(
                $exception->getMessage(),
                $exception->getCode(),
                $exception->getPrevious()
            );
        }
        $this->validateResponse($response);

        return $this->quoteArrayToQuoteDtoCollection($response['quotes']);
    }

    /**
     * @throws ClientException
     */
    private function validateResponse($response): void
    {
        if (empty($response['success'])
            || empty($response['quotes'])
            || $response['success'] !== true) {
            throw new ClientException(
                $response['error']['info'] ?? 'Malformed response object recieved',
                $response['error']['code'] ?? 0
            );
        }
    }

    /**
     * @throws ClientException
     */
    public function getAllExisting(): Collection
    {
        return $this->getAll([
            'currencies' => implode(',', Currency::pluck('iso')->all()),
        ]);
    }

    private function quoteArrayToQuoteDtoCollection(array $quotes): Collection
    {
        return collect(
            array_map(
                function ($currencyString, $exchangeRate) {
                    $currencyIso = strtoupper(
                        str_replace($this->defaultCurrency, '', $currencyString)
                    );
                    $this->validateQuote($currencyIso, $exchangeRate);

                    return new QuoteDTO(
                        $currencyIso,
                        $exchangeRate
                    );
                }, array_keys($quotes), $quotes
            )
        );
    }

    private function validateQuote(string $currencyIso, $exchangeRate): void
    {
        if (preg_match('/^[A-Z]{3}$/', $currencyIso)) {
            throw new InvalidArgumentException('Currency provided to is not in a valid format: '.$currencyIso);
        }

        // Check if $this->$exchange_rate is a float with 2 decimals
        if (preg_match('/^\d{1,6}(\.\d{1,2})?$/', $exchangeRate)) {
            throw new InvalidArgumentException('Exchange rate provided is not in a valid format: '.$exchangeRate);
        }
    }
}
