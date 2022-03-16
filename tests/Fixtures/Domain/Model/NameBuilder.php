<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\Domain\Model;

use App\Event\Domain\Model\Name;

/**
 * @psalm-immutable
 */
final class NameBuilder
{
    public function __construct(private string $name = 'Event name')
    {
    }

    public function withName(string $name): self
    {
        $new = clone $this;
        $new->name = $name;

        return $new;
    }

    public function build(): Name
    {
        return Name::fromString($this->name);
    }
}
