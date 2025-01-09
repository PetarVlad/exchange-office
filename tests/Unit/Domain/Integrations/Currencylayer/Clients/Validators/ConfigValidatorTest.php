<?php

namespace Tests\Unit\Domain\Integrations\Currencylayer\Clients\Validators;

use App\Domain\Integrations\Currencylayer\Currency\Clients\Validators\ConfigValidator;
use InvalidArgumentException;
use Tests\TestCase;

class ConfigValidatorTest extends TestCase
{
    /**
     * @dataProvider provideTestConfigValidatorData
     */
    public function test_config_validator(string $exceptionClass, $exceptionMessage, $config)
    {
        $this->expectException($exceptionClass);
        $this->expectExceptionMessage($exceptionMessage);
        ConfigValidator::validate($config);
    }

    public static function provideTestConfigValidatorData(): array
    {
        return [
            [
                InvalidArgumentException::class,
                'Config param integrations.currencylayer.host_url must be set',
                [],
            ],
            [
                InvalidArgumentException::class,
                'Config param integrations.currencylayer.host_url must be a valid url',
                ['host_url' => 'ABC'],
            ],
            [
                InvalidArgumentException::class,
                'Config param integrations.currencylayer.access_key must be set',
                ['host_url' => 'http://localhost/'],
            ],
            [
                InvalidArgumentException::class,
                'Config param currencies.default must be set',
                ['host_url' => 'http://localhost/', 'access_key' => 'ABCD1234'],
            ],
        ];
    }
}
