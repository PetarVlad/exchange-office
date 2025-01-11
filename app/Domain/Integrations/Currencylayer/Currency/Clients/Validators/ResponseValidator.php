<?php

namespace App\Domain\Integrations\Currencylayer\Currency\Clients\Validators;

use App\Domain\Integrations\Currencylayer\Currency\Exceptions\BadResponseException;
use App\Domain\Integrations\Generic\Currency\Clients\Validators\ValidatorInterface;

class ResponseValidator implements ValidatorInterface
{
    /**
     * @throws BadResponseException
     */
    public static function validate(array $data): void
    {
        if (empty($data['success'])
            || empty($data['quotes'])
            || $data['success'] !== true) {
            throw new BadResponseException(
                $data['error']['info'] ?? 'Malformed response object received',
                $data['error']['code'] ?? 0
            );
        }
    }
}
