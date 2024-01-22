<?php

namespace App\Providers;

use App\Domain\Currency\Services\Calculator\ExchangeCalculator;
use App\Domain\Currency\Services\Calculator\Pipes\DiscountPipe;
use App\Domain\Currency\Services\Calculator\Pipes\ExchangePipe;
use App\Domain\Currency\Services\Calculator\Pipes\SurchargePipe;
use App\Domain\Integrations\Currencylayer\Currency\Clients\Client;
use App\Domain\Integrations\Currencylayer\Currency\Services\UpdateService;
use App\Domain\Integrations\Generic\Currency\Clients\ClientInterface;
use App\Domain\Integrations\Generic\Currency\Services\UpdateServiceInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ExchangeCalculator::class, function (Application $app) {
            return new ExchangeCalculator(
                [
                    ExchangePipe::class,
                    SurchargePipe::class,
                    DiscountPipe::class,
                ],
                $app->make(Pipeline::class)
            );
        });

        $this->app->bind(ClientInterface::class, function () {
            return new Client(
                [
                    'default_currency' => config('currencies.default'),
                    ...config('integrations.currencylayer'),
                ]);
        });

        $this->app->bind(UpdateService::class, function (Application $app) {
            return new UpdateService($app->make(ClientInterface::class));
        });

        $this->app->bind(UpdateServiceInterface::class, UpdateService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
