<?php

namespace App\Domain\Integrations\Generic\Currency\Clients\Mappers;

use Illuminate\Support\Collection;

interface QuoteDtoMapperInterface
{
    public function mapToCollection(array $quotes): Collection;
}
