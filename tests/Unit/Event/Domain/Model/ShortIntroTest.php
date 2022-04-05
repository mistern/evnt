<?php

declare(strict_types=1);

namespace App\Tests\Unit\Event\Domain\Model;

use App\Event\Domain\Model\ShortIntro;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

use function App\Tests\Fixtures\Event\Domain\Model\aShortIntro;

final class ShortIntroTest extends TestCase
{
    public function testItCreatesWithProvidedValue(): void
    {
        $value = 'Short introduction';

        $shortIntro = aShortIntro()->withShortIntro($value)->build();

        self::assertSame($value, $shortIntro->toString());
    }

    public function testItFailsToCreateIfProvidedValueIsEmpty(): void
    {
        $this->expectExceptionObject(new InvalidArgumentException('Expected a non-empty value.'));

        /** @psalm-suppress UnusedMethodCall */
        aShortIntro()->withShortIntro('')->build();
    }

    public function testItFailsToCreateIfProvidedValueIsTooLong(): void
    {
        $tooLongLength = ShortIntro::MAX_LENGTH + 1;
        $tooLongValue = str_repeat('a', $tooLongLength);
        $this->expectExceptionObject(
            new InvalidArgumentException(
                sprintf(
                    'Expected a value to contain at most %s characters; got: "%s".',
                    ShortIntro::MAX_LENGTH,
                    $tooLongValue
                )
            )
        );

        /** @psalm-suppress UnusedMethodCall */
        aShortIntro()->withShortIntro($tooLongValue)->build();
    }

    public function testItEqualsToOtherShortIntroWithSameValue(): void
    {
        $value = 'Same short introduction.';
        $shortIntro = aShortIntro()->withShortIntro($value)->build();
        $otherShortIntro = aShortIntro()->withShortIntro($value)->build();

        self::assertTrue(
            $shortIntro->equals($otherShortIntro),
            sprintf(
                'Event Short Intro with value "%s" should be equal to other Event Short Intro with value "%s".',
                $shortIntro->toString(),
                $otherShortIntro->toString()
            )
        );
    }

    public function testItDoesNotEqualToOtherShortIntroWithDifferentValue(): void
    {
        $shortIntro = aShortIntro()->withShortIntro('First short introduction.')->build();
        $otherShortIntro = aShortIntro()->withShortIntro('Different short introduction.')->build();

        self::assertFalse(
            $shortIntro->equals($otherShortIntro),
            sprintf(
                'Event Short Intro with value "%s" should not be equal to other Event Short Intro with value "%s".',
                $shortIntro->toString(),
                $otherShortIntro->toString()
            )
        );
    }
}
