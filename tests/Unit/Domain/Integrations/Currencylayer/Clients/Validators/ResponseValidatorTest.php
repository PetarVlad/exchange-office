<?php

namespace Tests\Unit\Domain\Integrations\Currencylayer\Clients\Validators;

use App\Domain\Integrations\Currencylayer\Currency\Clients\Validators\ResponseValidator;
use App\Domain\Integrations\Currencylayer\Currency\Exceptions\BadResponseException;
use Tests\TestCase;

class ResponseValidatorTest extends TestCase
{
    /**
     * @dataProvider provideMalformedResponseData
     */
    public function test_response_validator(array $response): void
    {
        $this->expectException(BadResponseException::class);
        $this->expectExceptionMessage('Malformed response object received');
        ResponseValidator::validate($response);
    }

    public static function provideMalformedResponseData(): array
    {
        return [
            [
                [],
            ],
            [
                [
                    'success' => false,
                ],
            ],
            [
                [
                    'success' => true,
                ],
            ],
            [
                [
                    'success' => true,
                    'quotes' => [],
                ],
            ],
        ];
    }
}
