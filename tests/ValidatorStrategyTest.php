<?php

declare(strict_types=1);

namespace Ohtyap\LaravelSaneValidator\Tests;

use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Ohtyap\LaravelSaneValidator\Validator;
use PHPUnit\Framework\TestCase;

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

    /**
     * @dataProvider dataForValidation
     */
    public function testDefaultStrategyByStaticVariable(array $data, array $rules, bool $strategySaneValidatorPasses, bool $strategyLaravelPasses): void
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

    /**
     * @dataProvider dataForValidation
     */
    public function testEnableSaneValidationPasses(array $data, array $rules, bool $strategySaneValidatorPasses, bool $strategyLaravelPasses): void
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
        ];
    }
}
