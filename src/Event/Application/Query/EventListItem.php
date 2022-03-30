<?php

declare(strict_types=1);

namespace App\Event\Application\Query;

final class EventListItem
{
    public function __construct(
        public readonly string $id,
        public readonly string $name
    ) {
    }
}
