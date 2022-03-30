<?php

declare(strict_types=1);

namespace App\Event\Domain\Service\Exception;

use App\Event\Domain\Model\EventId;
use RuntimeException;

use function sprintf;

final class EventNotFound extends RuntimeException
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function byId(EventId $id): self
    {
        return new self(sprintf('Event by ID "%s" not found.', $id->toString()));
    }
}
