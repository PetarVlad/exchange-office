<?php

namespace App\Domain\Integrations\Currencylayer\Currency\Clients\Mappers;

use App\Domain\Integrations\Currencylayer\Currency\Clients\Validators\QuoteValidator;
use App\Domain\Integrations\Generic\Currency\Clients\Mappers\QuoteDtoMapperInterface;
use App\Domain\Integrations\Generic\Currency\DataTransferObjects\QuoteDto;
use Illuminate\Support\Collection;

class QuoteDtoMapper implements QuoteDtoMapperInterface
{
    public function __construct(private readonly string $defaultCurrency)
    {
    }

    public function mapToCollection(array $quotes): Collection
    {
        return collect(
            array_map(
                function ($currencyString, $exchangeRate) {
                    $currencyIso = strtoupper(
                        str_replace($this->defaultCurrency, '', $currencyString)
                    );
                    QuoteValidator::validate(
                        [
                            'currencyIso' => $currencyIso,
                            'exchangeRate' => $exchangeRate,
                        ]
                    );

                    return new QuoteDTO(
                        $currencyIso,
                        $exchangeRate
                    );
                }, array_keys($quotes), $quotes
            )
        );
    }
}
