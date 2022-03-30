<?php

declare(strict_types=1);

namespace App\Tests\Unit\Event\Infrastructure\Doctrine\DBAL\Types;

use App\Event\Domain\Model\Name;
use App\Event\Infrastructure\Doctrine\DBAL\Types\NameType;
use App\Tests\Doubles\Doctrine\PlatformDummy;
use Doctrine\DBAL\Types\ConversionException;
use PHPUnit\Framework\TestCase;

final class NameTypeTest extends TestCase
{
    /**
     * @throws ConversionException
     */
    public function testItConvertsDatabaseNullValueToPHPNullValue(): void
    {
        /** @psalm-suppress InternalMethod */
        $type = new NameType();

        $value = $type->convertToPHPValue(null, new PlatformDummy());

        self::assertNull($value, 'Failed to convert database null value to PHP null value.');
    }

    /**
     * @throws ConversionException
     */
    public function testItConvertsDatabaseStringValueToNameObject(): void
    {
        /** @psalm-suppress InternalMethod */
        $type = new NameType();

        $value = $type->convertToPHPValue($name = 'Event name', new PlatformDummy());

        self::assertNotNull($value);
        self::assertTrue(Name::fromString($name)->equals($value), 'Failed to convert database string to Name object.');
    }

    /**
     * @throws ConversionException
     */
    public function testItFailsToConvertUnsupportedDatabaseValueToPHPValue(): void
    {
        /** @psalm-suppress InternalMethod */
        $type = new NameType();
        $this->expectExceptionObject(
            ConversionException::conversionFailedInvalidType(
                $unsupportedDatabaseValue = 1,
                NameType::NAME,
                ['null', 'string']
            )
        );

        $type->convertToPHPValue($unsupportedDatabaseValue, new PlatformDummy());
    }

    /**
     * @throws ConversionException
     */
    public function testItConvertsPHPNullValueToDatabaseNullValue(): void
    {
        /** @psalm-suppress InternalMethod */
        $type = new NameType();

        $value = $type->convertToDatabaseValue(null, new PlatformDummy());

        self::assertNull($value, 'Failed to convert PHP null value to database null value.');
    }

    /**
     * @throws ConversionException
     */
    public function testItConvertsNameObjectToDatabaseStringValue(): void
    {
        /** @psalm-suppress InternalMethod */
        $type = new NameType();

        $value = $type->convertToDatabaseValue(Name::fromString($name = 'Event name'), new PlatformDummy());

        self::assertSame($name, $value, 'Failed to convert Name object to database string.');
    }

    /**
     * @throws ConversionException
     */
    public function testItFailsToConvertUnsupportedPHPValueToDatabaseValue(): void
    {
        /** @psalm-suppress InternalMethod */
        $type = new NameType();
        $this->expectExceptionObject(
            ConversionException::conversionFailedInvalidType(
                $unsupportedPHPValue = 1,
                NameType::NAME,
                ['null', Name::class]
            )
        );

        $type->convertToDatabaseValue($unsupportedPHPValue, new PlatformDummy());
    }

    public function testName(): void
    {
        /** @psalm-suppress InternalMethod */
        $type = new NameType();

        self::assertSame(NameType::NAME, $type->getName());
    }
}
