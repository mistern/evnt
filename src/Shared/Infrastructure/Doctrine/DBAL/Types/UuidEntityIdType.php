<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Doctrine\DBAL\Types;

use App\Shared\Domain\Model\UuidEntityId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Exception;

use function is_string;

abstract class UuidEntityIdType extends Type
{
    /**
     * @return class-string<UuidEntityId>
     */
    abstract protected static function getClassName(): string;

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getGuidTypeDeclarationSQL($column);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?UuidEntityId
    {
        if (null === $value) {
            return null;
        }
        if (is_string($value)) {
            try {
                return (static::getClassName())::fromString($value);
            } catch (Exception $exception) {
                throw ConversionException::conversionFailed($value, $this->getName(), $exception);
            }
        }

        throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', 'string']);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }
        if ($value instanceof (static::getClassName())) {
            return $value->toString();
        }

        throw ConversionException::conversionFailedInvalidType(
            $value,
            $this->getName(),
            ['null', static::getClassName()]
        );
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
