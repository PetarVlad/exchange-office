<?php

namespace App\Domain\Integrations\Currencylayer\Currency\Clients;

use App\Domain\Currency\Models\Currency;
use App\Domain\Integrations\Currencylayer\Currency\Clients\Validators\ConfigValidator;
use App\Domain\Integrations\Currencylayer\Currency\Clients\Validators\ResponseValidator;
use App\Domain\Integrations\Currencylayer\Currency\Exceptions\ClientException;
use App\Domain\Integrations\Generic\Currency\Clients\ClientInterface;
use App\Domain\Integrations\Generic\Currency\Clients\Mappers\QuoteDtoMapperInterface;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class Client implements ClientInterface
{
    private string $hostUrl;

    private string $accessKey;

    private string $defaultCurrency;

    public function __construct(
        array $config,
        private readonly QuoteDtoMapperInterface $quoteDtoMapper
    ) {
        ConfigValidator::validate($config);
        $this->hostUrl = $config['host_url'];
        $this->accessKey = $config['access_key'];
        $this->defaultCurrency = $config['default_currency'];
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

            ResponseValidator::validate($response->json());

            return $this->quoteDtoMapper->mapToCollection($response['quotes']);
        } catch (Exception $exception) {
            throw new ClientException(
                $exception->getMessage(),
                $exception->getCode(),
                $exception->getPrevious()
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
}
