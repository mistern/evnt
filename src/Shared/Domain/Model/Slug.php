<?php

declare(strict_types=1);

namespace App\Shared\Domain\Model;

use Webmozart\Assert\Assert;

/**
 * @psalm-immutable
 */
final class Slug
{
    public const MAX_LENGTH = 100;

    private function __construct(private string $slug)
    {
        Assert::stringNotEmpty($this->slug, 'Expected a non-empty value.');
        Assert::maxLength(
            $this->slug,
            self::MAX_LENGTH,
            'Expected a value to contain at most %2$s characters; got: %s.'
        );
        Assert::regex(
            $this->slug,
            '/^[a-z0-9-]+$/',
            'Expected a value to contain lower case letters, digits and dash only; got: %s.'
        );
    }

    /**
     * @psalm-pure
     */
    public static function fromString(string $slug): self
    {
        return new self($slug);
    }

    public function toString(): string
    {
        return $this->slug;
    }

    public function equals(self $otherSlug): bool
    {
        return $this->slug === $otherSlug->slug;
    }
}
