<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\Model;

use App\Shared\Domain\Model\Slug;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

use function aSlug;
use function sprintf;

final class SlugTest extends TestCase
{
    public function testItCreatesFromProvidedValue(): void
    {
        $value = 'slug-1';

        $slug = aSlug()->withSlug($value)->build();

        self::assertSame($value, $slug->toString(), 'Slug was not created with provided value.');
    }

    public function testItFailsToCreateIfValueIsEmpty(): void
    {
        $this->expectExceptionObject(new InvalidArgumentException('Expected a non-empty value.'));

        /** @psalm-suppress UnusedMethodCall */
        aSlug()->withSlug('')->build();
    }

    public function testItFailsToCreateIfValueIsTooLong(): void
    {
        $tooLongLength = Slug::MAX_LENGTH + 1;
        $tooLongValue = str_repeat('a', $tooLongLength);
        $this->expectExceptionObject(
            new InvalidArgumentException(
                sprintf(
                    'Expected a value to contain at most %s characters; got: "%s".',
                    Slug::MAX_LENGTH,
                    $tooLongValue
                )
            )
        );

        /** @psalm-suppress UnusedMethodCall */
        aSlug()->withSlug($tooLongValue)->build();
    }

    /**
     * @dataProvider restrictedCharacters
     */
    public function testIfFailsToCreateIfValueContainsRestrictedCharacters(string $value): void
    {
        $this->expectExceptionObject(
            new InvalidArgumentException(
                sprintf('Expected a value to contain lower case letters, digits and dash only; got: "%s".', $value)
            )
        );

        /** @psalm-suppress UnusedMethodCall */
        aSlug()->withSlug($value)->build();
    }

    /**
     * @return array<string, array{0: string}>
     */
    public function restrictedCharacters(): array
    {
        return [
            'Uppercase' => ['A'],
            'Symbol' => ['!'],
            'UTF-8 uppercase' => ['Ą'],
            'UTF-8 lowercase' => ['ą'],
        ];
    }

    public function testItEqualsToOtherNameWithSameValue(): void
    {
        $value = 'slug-1';
        $slug = aSlug()->withSlug($value)->build();
        $otherSlug = aSlug()->withSlug($value)->build();

        self::assertTrue(
            $slug->equals($otherSlug),
            sprintf(
                'Slug with value "%s" should be equal to other Slug with value "%s".',
                $slug->toString(),
                $otherSlug->toString()
            )
        );
    }

    public function testItDoesNotEqualToOtherNameWithDifferentValue(): void
    {
        $slug = aSlug()->withSlug('slug-1')->build();
        $otherSlug = aSlug()->withSlug('slug-2')->build();

        self::assertFalse(
            $slug->equals($otherSlug),
            sprintf(
                'Slug with value "%s" should not be equal to other Slug with value "%s".',
                $slug->toString(),
                $otherSlug->toString()
            )
        );
    }
}
