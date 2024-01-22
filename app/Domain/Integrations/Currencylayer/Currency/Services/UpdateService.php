<?php

namespace App\Domain\Integrations\Currencylayer\Currency\Services;

use App\Domain\Currency\Models\Currency;
use App\Domain\Integrations\Currencylayer\Currency\Exceptions\ClientException;
use App\Domain\Integrations\Currencylayer\Currency\Exceptions\UpdateServiceException;
use App\Domain\Integrations\Generic\Currency\Clients\ClientInterface;
use App\Domain\Integrations\Generic\Currency\DataTransferObjects\QuoteDto;
use App\Domain\Integrations\Generic\Currency\Services\UpdateServiceInterface;
use Illuminate\Support\Facades\DB;

class UpdateService implements UpdateServiceInterface
{
    public function __construct(private readonly ClientInterface $client)
    {
    }

    /**
     * @throws UpdateServiceException
     */
    public function updateAll(): void
    {
        try {
            $liveData = $this->client->getAllExisting()->mapWithKeys(function (QuoteDTO $quote) {
                return [$quote->currency_iso => $quote->exchange_rate];
            })->all();
            $currencies = Currency::all();

            DB::transaction(function () use ($currencies, $liveData) {
                foreach ($currencies as $currency) {
                    $currency->exchange_rate = $liveData[$currency->iso];
                    $currency->save();
                }
            });
        } catch (ClientException $exception) {
            throw new UpdateServiceException(
                'Client error: ' . $exception->getMessage(),
                $exception->getCode(),
                $exception->getPrevious()
            );
        } catch(\Exception $exception){
            throw new UpdateServiceException(
                $exception->getMessage(),
                $exception->getCode(),
                $exception->getPrevious()
            );
        }
    }
}
