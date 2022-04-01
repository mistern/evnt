<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Doctrine\DBAL\Types;

use App\Shared\Domain\Model\Slug;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

final class SlugType extends Type
{
    public const NAME = 'slug';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $column['length'] = Slug::MAX_LENGTH;

        return $platform->getVarcharTypeDeclarationSQL($column);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Slug
    {
        if (null === $value) {
            return null;
        }
        if (is_string($value)) {
            return Slug::fromString($value);
        }

        throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', 'string']);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }
        if ($value instanceof Slug) {
            return $value->toString();
        }

        throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', Slug::class]);
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
