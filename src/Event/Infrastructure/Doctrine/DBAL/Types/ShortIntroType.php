<?php

declare(strict_types=1);

namespace App\Event\Infrastructure\Doctrine\DBAL\Types;

use App\Event\Domain\Model\ShortIntro;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\TextType;
use Throwable;

use function is_string;

final class ShortIntroType extends TextType
{
    public const NAME = 'event.short_intro';

    public function convertToPHPValue($value, AbstractPlatform $platform): ?ShortIntro
    {
        if (null === $value) {
            return null;
        }
        if (is_string($value)) {
            try {
                return ShortIntro::fromString($value);
            } catch (Throwable $exception) {
                throw ConversionException::conversionFailed($value, $this->getShortIntro(), $exception);
            }
        }

        throw ConversionException::conversionFailedInvalidType($value, $this->getShortIntro(), ['null', 'string']);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value || is_string($value)) {
            return $value;
        }
        if ($value instanceof ShortIntro) {
            return $value->toString();
        }

        throw ConversionException::conversionFailedInvalidType(
            $value,
            $this->getShortIntro(),
            ['null', ShortIntro::class]
        );
    }

    public function getShortIntro(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
