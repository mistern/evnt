<?php

declare(strict_types=1);

namespace App\Event\Domain\Model;

use App\Shared\Domain\Model\UuidEntityId;

/**
 * @psalm-immutable
 */
final class EventId extends UuidEntityId
{
}
