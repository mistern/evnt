<?php

declare(strict_types=1);

namespace App\Event\Infrastructure\Doctrine\DBAL\Types;

use App\Event\Domain\Model\EventId;
use App\Shared\Infrastructure\Doctrine\DBAL\Types\UuidEntityIdType;

final class EventIdType extends UuidEntityIdType
{
    public const NAME = 'event.event_id';

    protected static function getClassName(): string
    {
        return EventId::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
