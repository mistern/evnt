<?php

declare(strict_types=1);

namespace App\Tests\Unit\Event\Domain\Service\Exception;

use App\Event\Domain\Service\Exception\EventIdGenerationFailed;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class EventIdGenerationFailedTest extends TestCase
{
    public function testItCreatesExceptionWithUnexpectedException(): void
    {
        $exception = EventIdGenerationFailed::unexpectedly(
            $unexpectedException = new RuntimeException('Unexpected error.')
        );

        self::assertSame(
            $unexpectedException,
            $exception->getPrevious(),
            'Exception was not created with unexpected exception.'
        );
    }

    public function testItCreatesExceptionWithDefaultMessage(): void
    {
        $exception = EventIdGenerationFailed::unexpectedly(new RuntimeException('Unexpected error.'));

        self::assertSame(
            'Failed to generate next Event ID.',
            $exception->getMessage(),
            'Exception was not created with default message.'
        );
    }
}
