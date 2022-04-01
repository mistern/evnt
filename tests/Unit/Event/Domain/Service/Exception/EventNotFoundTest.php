<?php

declare(strict_types=1);

namespace App\Tests\Unit\Event\Domain\Service\Exception;

use App\Event\Domain\Service\Exception\EventNotFound;
use PHPUnit\Framework\TestCase;

use function App\Tests\Fixtures\Event\Domain\Model\anEventId;
use function sprintf;

final class EventNotFoundTest extends TestCase
{
    public function testItCreatesExceptionWithProvidedId(): void
    {
        $exception = EventNotFound::byId($id = anEventId()->build());

        self::assertSame(
            sprintf('Event by ID "%s" not found.', $id->toString()),
            $exception->getMessage(),
            'Exception with provided ID was not created.'
        );
    }
}
