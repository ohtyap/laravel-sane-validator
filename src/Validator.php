<?php

declare(strict_types=1);

namespace Ohtyap\LaravelSaneValidator;

use Illuminate\Contracts\Translation\Translator;
use Illuminate\Validation\Validator as IlluminateValidator;

class Validator extends IlluminateValidator
{
    private static bool $defaultEnableSaneValidation =  true;
    private ?bool $enabledSaneValidation;

    public function __construct(Translator $translator, array $data, array $rules, array $messages = [], array $attributes = [])
    {
        parent::__construct($translator, $data, $rules, $messages, $attributes);

        $this->enabledSaneValidation = self::$defaultEnableSaneValidation;
    }

    public static function defaultEnableSaneValidation(bool $defaultEnableSaneValidation): void
    {
        self::$defaultEnableSaneValidation = $defaultEnableSaneValidation;
    }

    public function enableSaneValidation(): void
    {
        $this->enabledSaneValidation = true;
    }

    public function disableSaneValidation(): void
    {
        $this->enabledSaneValidation = false;
    }

    private function isUsingSaneValidationBehavior(): bool
    {
        return $this->enabledSaneValidation;
    }

    protected function presentOrRuleIsImplicit($rule, $attribute, $value)
    {
        if (!$this->isUsingSaneValidationBehavior()) {
            return parent::presentOrRuleIsImplicit($rule, $attribute, $value);
        }

        return $this->validatePresent($attribute, $value) || $this->isImplicit($rule);
    }

    public function validateRequired($attribute, $value)
    {
        if (!$this->isUsingSaneValidationBehavior()) {
            return parent::validateRequired($attribute, $value);
        }

        return $this->validatePresent($attribute, $value);
    }

    public function validateFilled($attribute, $value)
    {
        if (!$this->isUsingSaneValidationBehavior()) {
            return parent::validateFilled($attribute, $value);
        }

        if (!$this->validatePresent($attribute, $value)) {
            return true;
        }

        if ($value === null && $this->hasRule($attribute, ['Nullable'])) {
            return true;
        }

        return parent::validateRequired($attribute, $value);
    }
}
