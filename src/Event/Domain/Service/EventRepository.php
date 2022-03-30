<?php

declare(strict_types=1);

namespace App\Event\Domain\Service;

use App\Event\Domain\Model\Event;
use App\Event\Domain\Model\EventId;
use App\Event\Domain\Service\Exception\EventIdGenerationFailed;
use App\Event\Domain\Service\Exception\EventNotFound;

interface EventRepository
{
    /**
     * @throws EventNotFound
     */
    public function getById(EventId $id): Event;

    public function store(Event $event): void;

    /**
     * @throws EventIdGenerationFailed
     */
    public function getNextId(): EventId;
}
