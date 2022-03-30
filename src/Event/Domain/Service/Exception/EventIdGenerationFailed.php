<?php

declare(strict_types=1);

namespace App\Event\Domain\Service\Exception;

use Exception;
use RuntimeException;

final class EventIdGenerationFailed extends RuntimeException
{
    private function __construct(Exception $previous)
    {
        parent::__construct('Failed to generate next Event ID.', 0, $previous);
    }

    public static function unexpectedly(Exception $exception): self
    {
        return new self($exception);
    }
}
