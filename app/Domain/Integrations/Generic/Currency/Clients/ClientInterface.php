<?php

namespace App\Domain\Integrations\Generic\Currency\Clients;

use Illuminate\Support\Collection;

interface ClientInterface
{
    public function getAll(array $params = []): Collection;

    public function getAllExisting(): Collection;
}
