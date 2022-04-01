<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\Shared\Domain\Model;

use App\Shared\Domain\Model\Slug;

/**
 * @psalm-immutable
 */
final class SlugBuilder
{
    public function __construct(private string $slug = 'slug-1')
    {
    }

    public function withSlug(string $slug): self
    {
        $new = clone $this;
        $new->slug = $slug;

        return $new;
    }

    public function build(): Slug
    {
        return Slug::fromString($this->slug);
    }
}
