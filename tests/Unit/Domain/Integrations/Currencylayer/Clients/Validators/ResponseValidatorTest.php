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
    public function testResponseValidator(array $response): void
    {
        $this->expectException(BadResponseException::class);
        $this->expectExceptionMessage('Malformed response object recieved');
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
