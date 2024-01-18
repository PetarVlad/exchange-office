<?php

namespace App\Providers;

use App\Domain\Currency\Services\Calculator\CurrencyExchangeCalculator;
use App\Domain\Currency\Services\Calculator\Pipes\DiscountPipe;
use App\Domain\Currency\Services\Calculator\Pipes\ExchangePipe;
use App\Domain\Currency\Services\Calculator\Pipes\SurchargePipe;
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
        $this->app->bind(CurrencyExchangeCalculator::class, function (Application $app) {
            return new CurrencyExchangeCalculator(
                [
                    ExchangePipe::class,
                    SurchargePipe::class,
                    DiscountPipe::class,
                ],
                $app->make(Pipeline::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
