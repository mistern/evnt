<?php

declare(strict_types=1);

namespace App\Event\Infrastructure\Db;

use App\Event\Domain\Model\Event;
use App\Event\Domain\Model\EventId;
use App\Event\Domain\Service\EventRepository;
use App\Event\Domain\Service\Exception\EventIdGenerationFailed;
use App\Event\Domain\Service\Exception\EventNotFound;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Ramsey\Uuid\UuidFactoryInterface;

final class PgSqlEventRepository implements EventRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UuidFactoryInterface $uuidFactory
    ) {
    }

    public function getById(EventId $id): Event
    {
        $event = $this->entityManager->find(Event::class, $id);
        if (null === $event) {
            throw EventNotFound::byId($id);
        }

        return $event;
    }

    public function store(Event $event): void
    {
        $this->entityManager->persist($event);
        $this->entityManager->flush();
    }

    public function getNextId(): EventId
    {
        try {
            return EventId::fromString($this->uuidFactory->uuid4()->toString());
        } catch (Exception $exception) {
            throw EventIdGenerationFailed::unexpectedly($exception);
        }
    }
}
