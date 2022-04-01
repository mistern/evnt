<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Infrastructure\Doctrine\DBAL\Types;

use App\Shared\Domain\Model\Slug;
use App\Shared\Infrastructure\Doctrine\DBAL\Types\SlugType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use PHPUnit\Framework\TestCase;

final class SlugTypeTest extends TestCase
{
    public function testItCreatesSQLDeclaration(): void
    {
        /** @psalm-suppress InternalMethod */
        $type = new SlugType();
        $platform = $this->createMock(AbstractPlatform::class);
        $platform
            ->method('getVarcharTypeDeclarationSQL')
            ->with(['length' => Slug::MAX_LENGTH])
            ->willReturn($expectedDeclaration = 'slug declaration');

        $actualDeclaration = $type->getSQLDeclaration([], $platform);

        self::assertSame($expectedDeclaration, $actualDeclaration, 'Failed asserting that Slug type was created.');
    }

    public function testSlug(): void
    {
        /** @psalm-suppress InternalMethod */
        $type = new SlugType();

        self::assertSame(SlugType::NAME, $type->getName());
    }

    /**
     * @throws ConversionException
     */
    public function testItConvertsDatabaseNullValueToPHPNullValue(): void
    {
        /** @psalm-suppress InternalMethod */
        $type = new SlugType();

        $value = $type->convertToPHPValue(null, $this->createMock(AbstractPlatform::class));

        self::assertNull($value, 'Failed to convert database null value to PHP null value.');
    }

    /**
     * @throws ConversionException
     */
    public function testItConvertsDatabaseStringValueToSlugObject(): void
    {
        /** @psalm-suppress InternalMethod */
        $type = new SlugType();

        $value = $type->convertToPHPValue($slug = 'slug-1', $this->createMock(AbstractPlatform::class));

        self::assertNotNull($value);
        self::assertEquals(Slug::fromString($slug), $value, 'Failed to convert database string to Slug object.');
    }

    /**
     * @throws ConversionException
     */
    public function testItFailsToConvertUnsupportedDatabaseValueToPHPValue(): void
    {
        /** @psalm-suppress InternalMethod */
        $type = new SlugType();
        $this->expectExceptionObject(
            ConversionException::conversionFailedInvalidType(
                $unsupportedDatabaseValue = 1,
                SlugType::NAME,
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
        $type = new SlugType();

        $value = $type->convertToDatabaseValue(null, $this->createMock(AbstractPlatform::class));

        self::assertNull($value, 'Failed to convert PHP null value to database null value.');
    }

    /**
     * @throws ConversionException
     */
    public function testItConvertsSlugObjectToDatabaseStringValue(): void
    {
        /** @psalm-suppress InternalMethod */
        $type = new SlugType();

        $value = $type->convertToDatabaseValue(
            Slug::fromString($slug = 'slug-1'),
            $this->createMock(AbstractPlatform::class)
        );

        self::assertSame($slug, $value, 'Failed to convert Slug object to database string.');
    }

    /**
     * @throws ConversionException
     */
    public function testItFailsToConvertUnsupportedPHPValueToDatabaseValue(): void
    {
        /** @psalm-suppress InternalMethod */
        $type = new SlugType();
        $this->expectExceptionObject(
            ConversionException::conversionFailedInvalidType(
                $unsupportedPHPValue = 1,
                SlugType::NAME,
                ['null', Slug::class]
            )
        );

        $type->convertToDatabaseValue($unsupportedPHPValue, $this->createMock(AbstractPlatform::class));
    }
}
