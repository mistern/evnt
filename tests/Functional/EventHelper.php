<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Event\Domain\Model\Event;
use App\Event\Domain\Service\EventRepository;
use App\Tests\Fixtures\Event\Domain\Model\EventBuilder;

trait EventHelper
{
    private ?EventRepository $repository = null;

    private function storeEvents(EventBuilder|Event ...$events): void
    {
        $repository = $this->getRepository();
        foreach ($events as $event) {
            if ($event instanceof EventBuilder) {
                $event = $event->build();
            }
            $repository->store($event);
        }
    }

    private function getRepository(): EventRepository
    {
        return $this->repository ?? $this->repository = self::bootKernel()->getContainer()->get(EventRepository::class);
    }
}
