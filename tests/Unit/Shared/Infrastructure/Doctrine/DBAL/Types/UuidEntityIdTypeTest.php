<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Infrastructure\Doctrine\DBAL\Types;

use App\Tests\Doubles\Doctrine\PlatformDummy;
use App\Tests\Doubles\Doctrine\PlatformStub;
use App\Tests\Unit\Shared\Domain\Model\BasicUuidEntityId;
use Doctrine\DBAL\Types\ConversionException;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class UuidEntityIdTypeTest extends TestCase
{
    public function testItCreatesPlatformSpecificGuidTypeSQLDeclaration(): void
    {
        /** @psalm-suppress InternalMethod */
        $type = new BasicUuidEntityIdType();
        $platform = new PlatformStub();
        $platform->guidTypeDeclarationSQL = 'guid type declaration';

        $declaration = $type->getSQLDeclaration([], $platform);

        self::assertSame($platform->guidTypeDeclarationSQL, $declaration);
    }

    /**
     * @throws ConversionException
     */
    public function testItConvertsDatabaseNullValueToPHPNullValue(): void
    {
        /** @psalm-suppress InternalMethod */
        $type = new BasicUuidEntityIdType();

        $value = $type->convertToPHPValue(null, new PlatformDummy());

        self::assertNull($value, 'Failed to convert database null value to PHP null value.');
    }

    /**
     * @throws ConversionException
     */
    public function testItConvertsDatabaseStringValueToUuidEntityIdObject(): void
    {
        /** @psalm-suppress InternalMethod */
        $type = new BasicUuidEntityIdType();

        /** @var BasicUuidEntityId|null $value */
        $value = $type->convertToPHPValue($id = '48626056-e915-4843-bbe9-1f30625a5f8c', new PlatformDummy());

        self::assertNotNull($value);
        self::assertTrue(
            BasicUuidEntityId::fromString($id)->equals($value),
            'Failed to convert database string to UUID Entity ID object.'
        );
    }

    /**
     * @throws ConversionException
     */
    public function testItFailsToConvertUnsupportedDatabaseValueToPHPValue(): void
    {
        /** @psalm-suppress InternalMethod */
        $type = new BasicUuidEntityIdType();
        $this->expectExceptionObject(
            ConversionException::conversionFailedInvalidType(
                $unsupportedDatabaseValue = 1,
                BasicUuidEntityIdType::NAME,
                ['null', 'string']
            )
        );

        $type->convertToPHPValue($unsupportedDatabaseValue, new PlatformDummy());
    }

    public function testItFailsToConvertIfPHPValueCreationFails(): void
    {
        /** @psalm-suppress InternalMethod */
        $type = new BasicUuidEntityIdType();
        $this->expectExceptionObject(
            ConversionException::conversionFailed(
                $value = '',
                BasicUuidEntityIdType::NAME,
                $exception = new RuntimeException()
            )
        );

        $type->convertToPHPValue($value, new PlatformDummy());
    }

    /**
     * @throws ConversionException
     */
    public function testItConvertsPHPNullValueToDatabaseNullValue(): void
    {
        /** @psalm-suppress InternalMethod */
        $type = new BasicUuidEntityIdType();

        $value = $type->convertToDatabaseValue(null, new PlatformDummy());

        self::assertNull($value, 'Failed to convert PHP null value to database null value.');
    }

    /**
     * @throws ConversionException
     */
    public function testItConvertsUuidEntityIdObjectToDatabaseStringValue(): void
    {
        /** @psalm-suppress InternalMethod */
        $type = new BasicUuidEntityIdType();

        $value = $type->convertToDatabaseValue(
            BasicUuidEntityId::fromString($id = 'c9f9c097-5500-4d1d-be05-5d0f9dbb48bd'),
            new PlatformDummy()
        );

        self::assertSame($id, $value, 'Failed to convert UUID Entity ID object to database string.');
    }

    /**
     * @throws ConversionException
     */
    public function testItFailsToConvertUnsupportedPHPValueToDatabaseValue(): void
    {
        /** @psalm-suppress InternalMethod */
        $type = new BasicUuidEntityIdType();
        $this->expectExceptionObject(
            ConversionException::conversionFailedInvalidType(
                $unsupportedPHPValue = 1,
                BasicUuidEntityIdType::NAME,
                ['null', BasicUuidEntityId::class]
            )
        );

        $type->convertToDatabaseValue($unsupportedPHPValue, new PlatformDummy());
    }

    public function testItRequiresSQLCommentHint(): void
    {
        /** @psalm-suppress InternalMethod */
        $type = new BasicUuidEntityIdType();

        self::assertTrue($type->requiresSQLCommentHint(new PlatformDummy()), 'SQL comment hint was is not required.');
    }
}
