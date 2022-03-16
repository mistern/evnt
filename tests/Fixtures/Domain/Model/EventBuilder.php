<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\Domain\Model;

use App\Event\Domain\Model\Event;
use App\Event\Domain\Model\EventId;
use App\Event\Domain\Model\Name;

use function is_string;

/**
 * @psalm-immutable
 */
final class EventBuilder
{
    private EventId $id;
    private Name $name;

    public function __construct()
    {
        $this->id = anEventId()->build();
        $this->name = aName()->build();
    }

    public function withId(string|EventIdBuilder|EventId $id): self
    {
        $new = clone $this;
        $new->id = match (true) {
            is_string($id) => EventId::fromString($id),
            $id instanceof EventIdBuilder => $id->build(),
            default => $id,
        };

        return $new;
    }

    public function withName(string|NameBuilder|Name $name): self
    {
        $new = clone $this;
        $new->name = match (true) {
            is_string($name) => Name::fromString($name),
            $name instanceof NameBuilder => $name->build(),
            default => $name,
        };

        return $new;
    }

    public function build(): Event
    {
        return Event::register($this->id, $this->name);
    }
}
