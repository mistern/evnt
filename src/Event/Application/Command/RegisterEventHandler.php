<?php

declare(strict_types=1);

namespace App\Event\Application\Command;

use App\Event\Domain\Model\Event;
use App\Event\Domain\Model\EventId;
use App\Event\Domain\Model\Name;
use App\Event\Domain\Service\EventRepository;

final class RegisterEventHandler
{
    private EventRepository $repository;

    public function __construct(EventRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(RegisterEvent $command): void
    {
        $id = EventId::fromString($command->id);
        $name = Name::fromString($command->name);
        $event = Event::register($id, $name);
        $this->repository->store($event);
    }
}
