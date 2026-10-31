<?php

namespace Tests\Unit;

use App\Rules\PhoneNumber;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * The rule on its own, without HTTP or a database. (The contact form
 * feature tests check that the form actually uses it.)
 */
class PhoneNumberRuleTest extends TestCase
{
    private function passes(string $value): bool
    {
        $passed = true;
        (new PhoneNumber)->validate('phone', $value, function () use (&$passed) {
            $passed = false;
        });

        return $passed;
    }

    /**
     * @return array<string, array{string, bool}>
     */
    public static function numbers(): array
    {
        return [
            'saudi international' => ['+966 55 111 6121', true],
            'saudi local' => ['0551116121', true],
            'dashes and brackets' => ['(055) 111-6121', true],
            'dots' => ['055.111.6121', true],
            'exactly 8 digits' => ['12345678', true],
            'exactly 15 digits' => ['+123456789012345', true],
            '7 digits' => ['1234567', false],
            '16 digits' => ['1234567890123456', false],
            'letters' => ['055 111 ABCD', false],
            'plus not at the start' => ['055+1116121', false],
            'empty' => ['', false],
        ];
    }

    #[DataProvider('numbers')]
    public function test_phone_number_rule(string $value, bool $expected): void
    {
        $this->assertSame($expected, $this->passes($value));
    }
}
