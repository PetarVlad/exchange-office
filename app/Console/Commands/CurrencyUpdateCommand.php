<?php

namespace App\Console\Commands;

use App\Domain\Integrations\Generic\Currency\Services\UpdateServiceInterface;
use Exception;
use Illuminate\Console\Command;

class CurrencyUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:update';

    /**
     * The console command description.
     *
     * @var string|null
     */
    protected $description = 'Updates the exchange rates of the existing currencies in the DB';

    public function handle(UpdateServiceInterface $updateService): void
    {
        try {
            $updateService->updateAll();
            $this->info('Update completed successfuly!');
        } catch (Exception $e) {
            $this->error('An error occurred while trying to update currencies: '.$e->getMessage());
        }
    }
}
