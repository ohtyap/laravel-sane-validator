# laravel-sane-validator
This is a strongly opinionated patch of laravel's validator. When using the validator outside 
of an HTTP POST scope (e.g., validating some data structures), you might notice some 
shortcomings and unexpected behaviors from the validator. `laravel-sane-validator` tries 
fixing them.

## Installation

Install the package via `composer`:
```
composer require ohtyap/laravel-sane-validator
```

After that, declare to use `laravel-sane-validator` by setting the `resolver` in the validation factory:
```php
public function boot(): void
{
    $this->app->extend('validator', function (Factory $factory) {
        $factory->resolver(function (Translator $translator, array $data, array $rules, array $messages, array $attributes) {
            return new \Ohtyap\LaravelSaneValidator\Validator($translator, $data, $rules, $messages, $attributes);
        });

        return $factory;
    });
}
```

## Shortcomings of laravel's validator

The first shortcoming is how the validator is handling implicit rules in combination of empty
strings. The following quote is taken from the laravel documentation:

> By default, when an attribute being validated is not present or contains an empty string, 
> normal validation rules, including custom rules, are not run.

This has the effect that input data might bypass your validation - especially when it comes
to optional fields. 
```php
$rules = ['email' => 'email'];
$data = ['email' => ' '];

Validator::make($data, $rules)->passes(); // true
```
In the above example, the expectation would be that the validator fails because the input (a `space`) 
isn't (obviously) not a valid email address.

It's possible to enforce the validation of empty strings by using the `required` or `filled` rule. This is
not very intuitive for optional fields, and it is very easy to forget to add `filled` (or `sometimes|required`).

Moreover, this brings the second shortcoming: laravel is making assumptions about how a non-empty value should 
look. It is impossible to pass `null` or `[]` - even if both are perfectly valid values - when using `required`.
Consequently, combinations like `required|nullable` aren't possible.

## `laravel-sane-validator` behavior

When using this validator, implicit rules are always executed when the field is present in the input.
That way, it is no longer possible for empty strings to bypass validation.

`required` (including the other special cases of `required*` like `requiredIf` ) has the same behavior 
as `present`. It's only checking if the field is present in the input. More explicit evaluation of what is
considered valid is up to the developer and what is defined in the rules.

### Special note about `filled`
`filled` is using the same behavior as in the original validator. An empty string is still considered
invalid (same for an empty array, `null`, etc.). But it is possible to declare rules like `filled|nullable`.

## Strategies to start using `laravel-sane-validator`
By default you install and register `laravel-sane-validator` and you are good to go. But this might be troublesome
for existing and bigger application. A step-by-step approach might be a better way of changing the behavior of the 
validation.

### Enable the `laravel-sane-validator` behavior by default, disable is occasionally
```php
# `laravel-sane-validator` behavior
$validator = Validator::make($data, $rules);
$validator->passes();

# laravel default behavior
$validator = Validator::make($data, $rules);
$validator->disableSaneValidation();
$validator->passes();
```

### Disable the `laravel-sane-validator` behavior by default, enable is occasionally
```php
public function boot(): void
{
    \Ohtyap\LaravelSaneValidator\Validator::defaultEnableSaneValidation(false);
}
```

```php
# `laravel-sane-validator` behavior
$validator = Validator::make($data, $rules);
$validator->enableSaneValidation();
$validator->passes();

# laravel default behavior
$validator = Validator::make($data, $rules);
$validator->passes();
```

