<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\Event\Domain\Model;

use App\Event\Domain\Model\EventId;

/**
 * @psalm-immutable
 */
final class EventIdBuilder
{
    public function __construct(private string $id = 'dff32965-ff54-4d51-b8f5-efafed7c8b95')
    {
    }

    public function withId(string $id): self
    {
        $new = clone $this;
        $new->id = $id;

        return $new;
    }

    public function build(): EventId
    {
        return EventId::fromString($this->id);
    }
}
