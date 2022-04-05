<?php

declare(strict_types=1);

namespace App\Tests\Unit\Event\Infrastructure\Doctrine\DBAL\Types;

use App\Event\Domain\Model\ShortIntro;
use App\Event\Infrastructure\Doctrine\DBAL\Types\ShortIntroType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use PHPUnit\Framework\TestCase;

final class ShortIntroTypeTest extends TestCase
{
    public function testItCreatesClobTypeSQLDeclaration(): void
    {
        /** @psalm-suppress InternalMethod */
        $type = new ShortIntroType();
        $platform = $this->createMock(AbstractPlatform::class);
        $platform
            ->method('getClobTypeDeclarationSQL')
            ->willReturn($expectedDeclaration = 'short intro declaration');

        $actualDeclaration = $type->getSQLDeclaration([], $platform);

        self::assertSame(
            $expectedDeclaration,
            $actualDeclaration,
            'Failed asserting that SQL declaration of Short Intro type was created.'
        );
    }


    /**
     * @throws ConversionException
     */
    public function testItConvertsDatabaseNullValueToPHPNullValue(): void
    {
        /** @psalm-suppress InternalMethod */
        $type = new ShortIntroType();

        $value = $type->convertToPHPValue(null, $this->createMock(AbstractPlatform::class));

        self::assertNull($value, 'Failed to convert database null value to PHP null value.');
    }

    /**
     * @throws ConversionException
     */
    public function testItConvertsDatabaseStringValueToShortIntroObject(): void
    {
        /** @psalm-suppress InternalMethod */
        $type = new ShortIntroType();

        $value = $type->convertToPHPValue($name = 'Short introduction.', $this->createMock(AbstractPlatform::class));

        self::assertNotNull($value);
        self::assertTrue(
            ShortIntro::fromString($name)->equals($value),
            'Failed to convert database string to Short Intro object.'
        );
    }

    /**
     * @throws ConversionException
     */
    public function testItFailsToConvertUnsupportedDatabaseValueToPHPValue(): void
    {
        /** @psalm-suppress InternalMethod */
        $type = new ShortIntroType();
        $this->expectExceptionObject(
            ConversionException::conversionFailedInvalidType(
                $unsupportedDatabaseValue = 1,
                ShortIntroType::NAME,
                ['null', 'string']
            )
        );

        $type->convertToPHPValue($unsupportedDatabaseValue, $this->createMock(AbstractPlatform::class));
    }

    /**
     * @throws ConversionException
     */
    public function testItConvertsPHPNullValueToDatabaseNullValue(): void
    {
        /** @psalm-suppress InternalMethod */
        $type = new ShortIntroType();

        $value = $type->convertToDatabaseValue(null, $this->createMock(AbstractPlatform::class));

        self::assertNull($value, 'Failed to convert PHP null value to database null value.');
    }

    /**
     * @throws ConversionException
     */
    public function testItConvertsShortIntroObjectToDatabaseStringValue(): void
    {
        /** @psalm-suppress InternalMethod */
        $type = new ShortIntroType();

        $value = $type->convertToDatabaseValue(
            ShortIntro::fromString($name = 'Event name'),
            $this->createMock(AbstractPlatform::class)
        );

        self::assertSame($name, $value, 'Failed to convert Short Intro object to database string.');
    }

    /**
     * @throws ConversionException
     */
    public function testItFailsToConvertUnsupportedPHPValueToDatabaseValue(): void
    {
        /** @psalm-suppress InternalMethod */
        $type = new ShortIntroType();
        $this->expectExceptionObject(
            ConversionException::conversionFailedInvalidType(
                $unsupportedPHPValue = 1,
                ShortIntroType::NAME,
                ['null', ShortIntro::class]
            )
        );

        $type->convertToDatabaseValue($unsupportedPHPValue, $this->createMock(AbstractPlatform::class));
    }

    public function testShortIntroName(): void
    {
        /** @psalm-suppress InternalMethod */
        $type = new ShortIntroType();

        self::assertSame(ShortIntroType::NAME, $type->getShortIntro());
    }
}
