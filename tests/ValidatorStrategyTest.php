<?php

declare(strict_types=1);

namespace Ohtyap\LaravelSaneValidator\Tests;

use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Ohtyap\LaravelSaneValidator\Validator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Validator::class)]
class ValidatorStrategyTest extends TestCase
{
    private Translator $translator;

    protected function setUp(): void
    {
        $this->translator = new Translator(new ArrayLoader(), 'en');
    }

    protected function tearDown(): void
    {
        Validator::defaultEnableSaneValidation(true);
    }

    #[DataProvider('dataForValidation')]
    public function testSaneValidatorStrategy(array $data, array $rules, bool $strategySaneValidatorPasses, bool $strategyLaravelPasses): void
    {
        $validator = new Validator(
            $this->translator,
            $data,
            $rules
        );

        self::assertSame($strategySaneValidatorPasses, $validator->passes());

        $validator = new Validator(
            $this->translator,
            $data,
            $rules
        );
        $validator->disableSaneValidation();

        self::assertSame($strategyLaravelPasses, $validator->passes());
    }

    #[DataProvider('dataForValidation')]
    public function testLaravelStrategy(array $data, array $rules, bool $strategySaneValidatorPasses, bool $strategyLaravelPasses): void
    {
        Validator::defaultEnableSaneValidation(false);

        $validator = new Validator(
            $this->translator,
            $data,
            $rules
        );

        self::assertSame($strategyLaravelPasses, $validator->passes());

        $validator = new Validator(
            $this->translator,
            $data,
            $rules
        );
        $validator->enableSaneValidation();

        self::assertSame($strategySaneValidatorPasses, $validator->passes());
    }

    public static function dataForValidation(): array
    {
        return [
            [
                'data' => [
                    'email' => ' ',
                ],
                'rules' => [
                    'email' => 'email',
                ],
                'strategySaneValidatorPasses' => false,
                'strategyLaravelPasses' => true,
            ],
            [
                'data' => [
                    'email' => null,
                ],
                'rules' => [
                    'email' => 'required|sometimes|nullable|email',
                ],
                'strategySaneValidatorPasses' => true,
                'strategyLaravelPasses' => false,
            ],
            [
                'data' => [
                    'email' => null,
                ],
                'rules' => [
                    'email' => 'filled|nullable|email',
                ],
                'strategySaneValidatorPasses' => true,
                'strategyLaravelPasses' => false,
            ],
        ];
    }
}
