<?php

declare(strict_types=1);

namespace App\Event\Domain\Model;

use Webmozart\Assert\Assert;

/**
 * @psalm-immutable
 */
final class ShortIntro
{
    public const MAX_LENGTH = 1000;

    private function __construct(private string $shortIntro)
    {
        Assert::stringNotEmpty($this->shortIntro, 'Expected a non-empty value.');
        Assert::maxLength(
            $this->shortIntro,
            self::MAX_LENGTH,
            'Expected a value to contain at most %2$s characters; got: %s.'
        );
    }

    /**
     * @psalm-pure
     */
    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function toString(): string
    {
        return $this->shortIntro;
    }

    public function equals(self $other): bool
    {
        return $this->shortIntro === $other->shortIntro;
    }
}
