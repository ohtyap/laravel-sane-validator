# laravel-sane-validator
This is a strongly opinionated patch of laravels validator. When using the validator outside 
of an HTTP POST scope (e.g., validating some data structures), you might notice some 
shortcomings and unexpected behaviors from the validator. `laravel-sane-validator` tries 
fixing them.

## Shortcomings of laravels validator

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

Moreover, this brings the second shortcoming: They are making assumptions about how a non-empty string should 
look. It is impossible to pass `null` or `[]` - even if both are perfectly valid values.
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
