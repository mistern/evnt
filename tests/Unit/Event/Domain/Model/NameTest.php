<?php

declare(strict_types=1);

namespace App\Tests\Unit\Event\Domain\Model;

use App\Event\Domain\Model\Name;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

use function App\Tests\Fixtures\Domain\Model\aName;
use function sprintf;
use function str_repeat;

final class NameTest extends TestCase
{
    public function testItCreatesFromProvidedValue(): void
    {
        $value = 'Event name';
        $name = aName()->withName($value)->build();

        self::assertSame($value, $name->toString(), 'Event name was not created with provided value.');
    }

    public function testIfFailsToCreateIfValueIsEmpty(): void
    {
        $this->expectExceptionObject(new InvalidArgumentException('Expected a non-empty value.'));
        /** @psalm-suppress UnusedMethodCall */
        aName()->withName('')->build();
    }

    public function testItFailsToCreateIfValueIsTooLong(): void
    {
        $this->expectExceptionObject(new InvalidArgumentException('Expected a value'));
        /** @psalm-suppress UnusedMethodCall */
        aName()->withName(str_repeat('a', Name::MAX_LENGTH + 1))->build();
    }

    public function testItEqualsToOtherNameWithSameValue(): void
    {
        $value = 'Event name';
        $name = aName()->withName($value)->build();
        $otherName = aName()->withName($value)->build();

        self::assertTrue(
            $name->equals($otherName),
            sprintf(
                'Event Name with value "%s" should be equal to other Event Name with value "%s".',
                $name->toString(),
                $otherName->toString()
            )
        );
    }

    public function testItDoesNotEqualToOtherNameWithDifferentValue(): void
    {
        $name = aName()->withName('Event name')->build();
        $otherName = aName()->withName('Different event name')->build();

        self::assertFalse(
            $name->equals($otherName),
            sprintf(
                'Event Name with value "%s" should not be equal to other Event Name with value "%s".',
                $name->toString(),
                $otherName->toString()
            )
        );
    }
}
