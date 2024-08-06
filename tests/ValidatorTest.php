<?php

declare(strict_types=1);

namespace Ohtyap\LaravelSaneValidator\Tests;

use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Ohtyap\LaravelSaneValidator\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    private Translator $translator;

    protected function setUp(): void
    {
        $this->translator = new Translator(new ArrayLoader(), 'en');
    }

    /**
     * @dataProvider dataForValidation
     */
    public function testValidation(array $data, array $rules, bool $passes, array $failed): void
    {
        $validator = new Validator(
            $this->translator,
            $data,
            $rules
        );

        self::assertSame($passes, $validator->passes());
        self::assertSame($failed, $validator->failed());
    }

    public static function dataForValidation(): array
    {
        return [
            'required string - valid string given' => [
                'data' => [
                    'name' => 'John Doe',
                ],
                'rules' => [
                    'name' => 'required|string',
                ],
                'passes' => true,
                'failed' => [],
            ],
            'required string - empty string given' => [
                'data' => [
                    'name' => '',
                ],
                'rules' => [
                    'name' => 'required|string',
                ],
                'passes' => true,
                'failed' => [],
            ],
            'required string - null given' => [
                'data' => [
                    'name' => null,
                ],
                'rules' => [
                    'name' => 'required|string',
                ],
                'passes' => false,
                'failed' => ['name' => ['String' => []]],
            ],
            'required string - not set in data' => [
                'data' => [],
                'rules' => [
                    'name' => 'required|string',
                ],
                'passes' => false,
                'failed' => ['name' => ['Required' => []]],
            ],

            'optional string - valid string given' => [
                'data' => [
                    'name' => 'John Doe',
                ],
                'rules' => [
                    'name' => 'string',
                ],
                'passes' => true,
                'failed' => [],
            ],
            'optional string - empty string given' => [
                'data' => [
                    'name' => '',
                ],
                'rules' => [
                    'name' => 'string',
                ],
                'passes' => true,
                'failed' => [],
            ],
            'optional string - null given' => [
                'data' => [
                    'name' => null,
                ],
                'rules' => [
                    'name' => 'string',
                ],
                'passes' => false,
                'failed' => ['name' => ['String' => []]],
            ],
            'optional string with min length- empty string given' => [
                'data' => [
                    'name' => '',
                ],
                'rules' => [
                    'name' => 'string|min:1',
                ],
                'passes' => false,
                'failed' => ['name' => ['Min' => ['1']]],
            ],
            'optional string - not set in data' => [
                'data' => [],
                'rules' => [
                    'name' => 'string',
                ],
                'passes' => true,
                'failed' => [],
            ],

            'required nullable string - valid string given' => [
                'data' => [
                    'name' => 'John Doe',
                ],
                'rules' => [
                    'name' => 'required|nullable|string',
                ],
                'passes' => true,
                'failed' => [],
            ],
            'required nullable string - empty string given' => [
                'data' => [
                    'name' => '',
                ],
                'rules' => [
                    'name' => 'required|nullable|string',
                ],
                'passes' => true,
                'failed' => [],
            ],
            'required nullable string - null given' => [
                'data' => [
                    'name' => null,
                ],
                'rules' => [
                    'name' => 'required|nullable|string',
                ],
                'passes' => true,
                'failed' => [],
            ],
            'required nullable string - not set in data' => [
                'data' => [],
                'rules' => [
                    'name' => 'required|nullable|string',
                ],
                'passes' => false,
                'failed' => ['name' => ['Required' => []]],
            ],

            'filled string - valid string given' => [
                'data' => [
                    'name' => 'John Doe',
                ],
                'rules' => [
                    'name' => 'filled|string',
                ],
                'passes' => true,
                'failed' => [],
            ],
            'filled string - empty string given' => [
                'data' => [
                    'name' => '',
                ],
                'rules' => [
                    'name' => 'filled|string',
                ],
                'passes' => false,
                'failed' => ['name' => ['Filled' => []]],
            ],
            'filled string - null given' => [
                'data' => [
                    'name' => null,
                ],
                'rules' => [
                    'name' => 'filled|string',
                ],
                'passes' => false,
                'failed' => ['name' => ['Filled' => []]],
            ],
            'filled string - nullable and null given' => [
                'data' => [
                    'name' => null,
                ],
                'rules' => [
                    'name' => 'filled|nullable|string',
                ],
                'passes' => true,
                'failed' => [],
            ],
            'filled string - not set in data' => [
                'data' => [],
                'rules' => [
                    'name' => 'filled|string',
                ],
                'passes' => true,
                'failed' => [],
            ],

            'accepted - true given' => [
                'data' => [
                    'flag' => true,
                ],
                'rules' => [
                    'flag' => 'accepted',
                ],
                'passes' => true,
                'failed' => [],
            ],
            'accepted - empty string given' => [
                'data' => [
                    'flag' => '',
                ],
                'rules' => [
                    'flag' => 'accepted',
                ],
                'passes' => false,
                'failed' => ['flag' => ['Accepted' => []]],
            ],
            'accepted - null given' => [
                'data' => [
                    'flag' => null,
                ],
                'rules' => [
                    'flag' => 'accepted',
                ],
                'passes' => false,
                'failed' => ['flag' => ['Accepted' => []]],
            ],
            'accepted - not set in data' => [
                'data' => [],
                'rules' => [
                    'flag' => 'accepted',
                ],
                'passes' => false,
                'failed' => ['flag' => ['Accepted' => []]],
            ],

            'declined - false given' => [
                'data' => [
                    'flag' => false,
                ],
                'rules' => [
                    'flag' => 'declined',
                ],
                'passes' => true,
                'failed' => [],
            ],
            'declined - empty string given' => [
                'data' => [
                    'flag' => '',
                ],
                'rules' => [
                    'flag' => 'declined',
                ],
                'passes' => false,
                'failed' => ['flag' => ['Declined' => []]],
            ],
            'declined - null given' => [
                'data' => [
                    'flag' => null,
                ],
                'rules' => [
                    'flag' => 'declined',
                ],
                'passes' => false,
                'failed' => ['flag' => ['Declined' => []]],
            ],
            'declined - not set in data' => [
                'data' => [],
                'rules' => [
                    'flag' => 'declined',
                ],
                'passes' => false,
                'failed' => ['flag' => ['Declined' => []]],
            ],

            'present string - valid string given' => [
                'data' => [
                    'name' => 'John Doe',
                ],
                'rules' => [
                    'name' => 'present|string',
                ],
                'passes' => true,
                'failed' => [],
            ],
            'present string - empty string given' => [
                'data' => [
                    'name' => '',
                ],
                'rules' => [
                    'name' => 'present|string',
                ],
                'passes' => true,
                'failed' => [],
            ],
            'present string - null given' => [
                'data' => [
                    'name' => null,
                ],
                'rules' => [
                    'name' => 'present|string',
                ],
                'passes' => false,
                'failed' => ['name' => ['String' => []]],
            ],
            'present string - not set in data' => [
                'data' => [],
                'rules' => [
                    'name' => 'present|string',
                ],
                'passes' => false,
                'failed' => ['name' => ['Present' => []]],
            ],

            'prohibited string - valid string given' => [
                'data' => [
                    'name' => 'John Doe',
                ],
                'rules' => [
                    'name' => 'prohibited|string',
                ],
                'passes' => false,
                'failed' => ['name' => ['Prohibited' => []]],
            ],
            'prohibited string - empty string given' => [
                'data' => [
                    'name' => '',
                ],
                'rules' => [
                    'name' => 'prohibited|string',
                ],
                'passes' => false,
                'failed' => ['name' => ['Prohibited' => []]],
            ],
            'prohibited string - null given' => [
                'data' => [
                    'name' => null,
                ],
                'rules' => [
                    'name' => 'prohibited|string',
                ],
                'passes' => false,
                'failed' => ['name' => ['Prohibited' => [], 'String' => []]],
            ],
            'prohibited string - not set in data' => [
                'data' => [],
                'rules' => [
                    'name' => 'prohibited|string',
                ],
                'passes' => true,
                'failed' => [],
            ],

            'requiredIf string - valid string given' => [
                'data' => [
                    'name' => 'John Doe',
                    'anotherField' => 'given',
                ],
                'rules' => [
                    'name' => 'requiredIf:anotherField,given|string',
                    'anotherField' => 'string',
                ],
                'passes' => true,
                'failed' => [],
            ],
            'requiredIf string - empty string given' => [
                'data' => [
                    'name' => '',
                    'anotherField' => 'given',
                ],
                'rules' => [
                    'name' => 'requiredIf:anotherField,given|string',
                    'anotherField' => 'string',
                ],
                'passes' => true,
                'failed' => [],
            ],
            'requiredIf string - null given' => [
                'data' => [
                    'name' => null,
                    'anotherField' => 'given',
                ],
                'rules' => [
                    'name' => 'requiredIf:anotherField,given|string',
                    'anotherField' => 'string',
                ],
                'passes' => false,
                'failed' => ['name' => ['String' => []]],
            ],
            'requiredIf string - not set in data' => [
                'data' => [
                    'anotherField' => 'given',
                ],
                'rules' => [
                    'name' => 'requiredIf:anotherField,given|string',
                    'anotherField' => 'string',
                ],
                'passes' => false,
                'failed' => ['name' => ['RequiredIf' => ['anotherField', 'given']]],
            ],
            'requiredIf string - reference field is null' => [
                'data' => [
                    'anotherField' => null,
                ],
                'rules' => [
                    'name' => 'requiredIf:anotherField,null|string',
                    'anotherField' => 'nullable',
                ],
                'passes' => false,
                'failed' => ['name' => ['RequiredIf' => ['anotherField', 'null']]],
            ],

            'prohibitedIf string - valid string given' => [
                'data' => [
                    'name' => 'John Doe',
                    'anotherField' => 'given',
                ],
                'rules' => [
                    'name' => 'prohibitedIf:anotherField,given|string',
                    'anotherField' => 'string',
                ],
                'passes' => false,
                'failed' => ['name' => ['ProhibitedIf' => ['anotherField', 'given']]],
            ],
            'prohibitedIf string - empty string given' => [
                'data' => [
                    'name' => '',
                    'anotherField' => 'given',
                ],
                'rules' => [
                    'name' => 'prohibitedIf:anotherField,given|string',
                    'anotherField' => 'string',
                ],
                'passes' => false,
                'failed' => ['name' => ['ProhibitedIf' => ['anotherField', 'given']]],
            ],
            'prohibitedIf string - null given' => [
                'data' => [
                    'name' => null,
                    'anotherField' => 'given',
                ],
                'rules' => [
                    'name' => 'prohibitedIf:anotherField,given|string',
                    'anotherField' => 'string',
                ],
                'passes' => false,
                'failed' => ['name' => ['ProhibitedIf' => ['anotherField', 'given'], 'String' => []]],
            ],
            'prohibitedIf string - not set in data' => [
                'data' => [
                    'anotherField' => 'given',
                ],
                'rules' => [
                    'name' => 'prohibitedIf:anotherField,given|string',
                    'anotherField' => 'string',
                ],
                'passes' => true,
                'failed' => [],
            ],
            'prohibitedIf string - reference field is null' => [
                'data' => [
                    'anotherField' => null,
                ],
                'rules' => [
                    'name' => 'prohibitedIf:anotherField,null|string',
                    'anotherField' => 'nullable',
                ],
                'passes' => true,
                'failed' => [],
            ],

            'requiredUnless string - valid string given' => [
                'data' => [
                    'name' => 'John Doe',
                    'anotherField' => 'given',
                ],
                'rules' => [
                    'name' => 'requiredUnless:anotherField,not_given|string',
                    'anotherField' => 'string',
                ],
                'passes' => true,
                'failed' => [],
            ],
            'requiredUnless string - empty string given' => [
                'data' => [
                    'name' => '',
                    'anotherField' => 'given',
                ],
                'rules' => [
                    'name' => 'requiredUnless:anotherField,not_given|string',
                    'anotherField' => 'string',
                ],
                'passes' => true,
                'failed' => [],
            ],
            'requiredUnless string - null given' => [
                'data' => [
                    'name' => null,
                    'anotherField' => 'given',
                ],
                'rules' => [
                    'name' => 'requiredUnless:anotherField,not_given|string',
                    'anotherField' => 'string',
                ],
                'passes' => false,
                'failed' => ['name' => ['String' => []]],
            ],
            'requiredUnless string - not set in data' => [
                'data' => [
                    'anotherField' => 'given',
                ],
                'rules' => [
                    'name' => 'requiredUnless:anotherField,not_given|string',
                    'anotherField' => 'string',
                ],
                'passes' => false,
                'failed' => ['name' => ['RequiredUnless' => ['anotherField', 'not_given']]],
            ],
            'requiredUnless string - reference field is null' => [
                'data' => [
                    'anotherField' => null,
                ],
                'rules' => [
                    'name' => 'requiredUnless:anotherField,null|string',
                    'anotherField' => 'nullable',
                ],
                'passes' => true,
                'failed' => [],
            ],

            'prohibitedUnless string - valid string given' => [
                'data' => [
                    'name' => 'John Doe',
                    'anotherField' => 'given',
                ],
                'rules' => [
                    'name' => 'prohibitedUnless:anotherField,not_given|string',
                    'anotherField' => 'string',
                ],
                'passes' => false,
                'failed' => ['name' => ['ProhibitedUnless' => ['anotherField', 'not_given']]],
            ],
            'prohibitedUnless string - empty string given' => [
                'data' => [
                    'name' => '',
                    'anotherField' => 'given',
                ],
                'rules' => [
                    'name' => 'prohibitedUnless:anotherField,not_given|string',
                    'anotherField' => 'string',
                ],
                'passes' => false,
                'failed' => ['name' => ['ProhibitedUnless' => ['anotherField', 'not_given']]],
            ],
            'prohibitedUnless string - null given' => [
                'data' => [
                    'name' => null,
                    'anotherField' => 'given',
                ],
                'rules' => [
                    'name' => 'prohibitedUnless:anotherField,not_given|string',
                    'anotherField' => 'string',
                ],
                'passes' => false,
                'failed' => ['name' => ['ProhibitedUnless' => ['anotherField', 'not_given'], 'String' => []]],
            ],
            'prohibitedUnless string - not set in data' => [
                'data' => [
                    'anotherField' => 'given',
                ],
                'rules' => [
                    'name' => 'prohibitedUnless:anotherField,not_given|string',
                    'anotherField' => 'string',
                ],
                'passes' => true,
                'failed' => [],
            ],
            'prohibitedUnless string - reference field is null' => [
                'data' => [
                    'anotherField' => null,
                ],
                'rules' => [
                    'name' => 'prohibitedUnless:anotherField,null|string',
                    'anotherField' => 'nullable',
                ],
                'passes' => true,
                'failed' => [],
            ],
        ];
    }
}
