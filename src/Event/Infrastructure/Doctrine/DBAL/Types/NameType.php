<?php

declare(strict_types=1);

namespace App\Event\Infrastructure\Doctrine\DBAL\Types;

use App\Event\Domain\Model\Name;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\StringType;
use Throwable;

use function is_string;

final class NameType extends StringType
{
    public const NAME = 'event.name';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $column['length'] = Name::MAX_LENGTH;

        return parent::getSQLDeclaration($column, $platform);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Name
    {
        if (null === $value) {
            return null;
        }
        if (is_string($value)) {
            try {
                return Name::fromString($value);
            } catch (Throwable $exception) {
                throw ConversionException::conversionFailed($value, $this->getName(), $exception);
            }
        }

        throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', 'string']);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value || is_string($value)) {
            return $value;
        }
        if ($value instanceof Name) {
            return $value->toString();
        }

        throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', Name::class]);
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
