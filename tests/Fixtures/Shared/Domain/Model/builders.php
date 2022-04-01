<?php

declare(strict_types=1);

use App\Tests\Fixtures\Shared\Domain\Model\SlugBuilder;

/**
 * @psalm-pure
 */
function aSlug(): SlugBuilder
{
    return new SlugBuilder();
}
