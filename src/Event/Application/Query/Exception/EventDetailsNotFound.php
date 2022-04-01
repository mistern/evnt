<?php

declare(strict_types=1);

namespace App\Event\Application\Query\Exception;

use RuntimeException;

use function sprintf;

final class EventDetailsNotFound extends RuntimeException
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function bySlug(string $slug): self
    {
        return new self(sprintf('Event Details by slug "%s" not found.', $slug));
    }
}
