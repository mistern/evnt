<?php

declare(strict_types=1);

namespace App\Tests\Doubles\Event;

use App\Event\Domain\Model\Event;
use App\Event\Domain\Model\EventId;
use App\Event\Domain\Service\EventRepository;
use App\Event\Domain\Service\Exception\EventNotFound;

final class InMemoryEventRepository implements EventRepository
{
    /**
     * @var array<string, Event>
     */
    private array $events = [];

    public function getById(EventId $id): Event
    {
        return $this->events[$id->toString()] ?? throw EventNotFound::byId($id);
    }

    public function store(Event $event): void
    {
        $this->events[$event->getId()->toString()] = $event;
    }

    public function getNextId(): EventId
    {
        return EventId::fromString('af2863e1-67cc-41f2-89f2-0bbd1a38865a');
    }
}
