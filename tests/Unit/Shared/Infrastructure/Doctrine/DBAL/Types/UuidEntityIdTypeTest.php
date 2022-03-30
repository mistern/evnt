<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Infrastructure\Doctrine\DBAL\Types;

use App\Tests\Unit\Shared\Domain\Model\BasicUuidEntityId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class UuidEntityIdTypeTest extends TestCase
{
    public function testItCreatesPlatformSpecificGuidTypeSQLDeclaration(): void
    {
        /** @psalm-suppress InternalMethod */
        $type = new BasicUuidEntityIdType();
        $platform = $this->createMock(AbstractPlatform::class);
        $platform->method('getGuidTypeDeclarationSQL')->willReturn($expectedDeclaration = 'guid declaration');

        $actualDeclaration = $type->getSQLDeclaration([], $platform);

        self::assertSame(
            $expectedDeclaration,
            $actualDeclaration,
            'Failed asserting that platform specific GUID type was created.'
        );
    }

    /**
     * @throws ConversionException
     */
    public function testItConvertsDatabaseNullValueToPHPNullValue(): void
    {
        /** @psalm-suppress InternalMethod */
        $type = new BasicUuidEntityIdType();

        $value = $type->convertToPHPValue(null, $this->createMock(AbstractPlatform::class));

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
        $value = $type->convertToPHPValue(
            $id = '48626056-e915-4843-bbe9-1f30625a5f8c',
            $this->createMock(AbstractPlatform::class)
        );

        self::assertNotNull($value);
        self::assertEquals(
            BasicUuidEntityId::fromString($id),
            $value,
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

        $type->convertToPHPValue($unsupportedDatabaseValue, $this->createMock(AbstractPlatform::class));
    }

    /**
     * @throws ConversionException
     */
    public function testItFailsToConvertIfPHPValueCreationFails(): void
    {
        /** @psalm-suppress InternalMethod */
        $type = new BasicUuidEntityIdType();
        $this->expectExceptionObject(
            ConversionException::conversionFailed($value = '', BasicUuidEntityIdType::NAME, new RuntimeException())
        );

        $type->convertToPHPValue($value, $this->createMock(AbstractPlatform::class));
    }

    /**
     * @throws ConversionException
     */
    public function testItConvertsPHPNullValueToDatabaseNullValue(): void
    {
        /** @psalm-suppress InternalMethod */
        $type = new BasicUuidEntityIdType();

        $value = $type->convertToDatabaseValue(null, $this->createMock(AbstractPlatform::class));

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
            $this->createMock(AbstractPlatform::class)
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

        $type->convertToDatabaseValue($unsupportedPHPValue, $this->createMock(AbstractPlatform::class));
    }

    public function testItRequiresSQLCommentHint(): void
    {
        /** @psalm-suppress InternalMethod */
        $type = new BasicUuidEntityIdType();

        self::assertTrue(
            $type->requiresSQLCommentHint($this->createMock(AbstractPlatform::class)),
            'SQL comment hint was is not required.'
        );
    }
}
