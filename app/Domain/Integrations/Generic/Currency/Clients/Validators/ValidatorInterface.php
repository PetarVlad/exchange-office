<?php

namespace App\Domain\Integrations\Generic\Currency\Clients\Validators;

interface ValidatorInterface
{
    public static function validate(array $data): void;
}
