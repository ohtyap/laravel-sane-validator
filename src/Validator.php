<?php
declare(strict_types=1);

namespace Ohtyap\LaravelSaneValidator;

use Illuminate\Validation\Validator as IlluminateValidator;

class Validator extends IlluminateValidator
{
    protected function presentOrRuleIsImplicit($rule, $attribute, $value)
    {
        return $this->validatePresent($attribute, $value) || $this->isImplicit($rule);
    }

    public function validateRequired($attribute, $value)
    {
        return $this->validatePresent($attribute, $value);
    }

    public function validateFilled($attribute, $value)
    {
        if (!$this->validatePresent($attribute, $value)) {
            return true;
        }

        if ($value === null && $this->hasRule($attribute, ['Nullable'])) {
            return true;
        }

        return parent::validateRequired($attribute, $value);
    }
}
