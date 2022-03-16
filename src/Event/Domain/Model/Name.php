<?php

declare(strict_types=1);

namespace App\Event\Domain\Model;

use Webmozart\Assert\Assert;

/**
 * @psalm-immutable
 */
final class Name
{
    public const MAX_LENGTH = 100;

    private function __construct(private string $name)
    {
        Assert::stringNotEmpty($this->name, 'Expected a non-empty value.');
        Assert::maxLength(
            $this->name,
            self::MAX_LENGTH,
            'Expected a value to contain at most %2$s characters; got: %s.'
        );
    }

    /**
     * @psalm-pure
     */
    public static function fromString(string $name): self
    {
        return new self($name);
    }

    public function toString(): string
    {
        return $this->name;
    }

    public function equals(self $other): bool
    {
        return $this->name === $other->name;
    }
}
