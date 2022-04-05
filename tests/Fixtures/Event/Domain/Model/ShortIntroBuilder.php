<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\Event\Domain\Model;

use App\Event\Domain\Model\ShortIntro;

/**
 * @psalm-immutable
 */
final class ShortIntroBuilder
{
    public function __construct(private string $shortIntro = 'Short introduction.')
    {
    }

    public function withShortIntro(string $shortIntro): self
    {
        $new = clone $this;
        $new->shortIntro = $shortIntro;

        return $new;
    }

    public function build(): ShortIntro
    {
        return ShortIntro::fromString($this->shortIntro);
    }
}
