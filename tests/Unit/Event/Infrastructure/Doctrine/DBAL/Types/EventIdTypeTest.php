<?php

declare(strict_types=1);

namespace App\Tests\Unit\Event\Infrastructure\Doctrine\DBAL\Types;

use App\Event\Infrastructure\Doctrine\DBAL\Types\EventIdType;
use PHPUnit\Framework\TestCase;

final class EventIdTypeTest extends TestCase
{
    public function testName(): void
    {
        /** @psalm-suppress InternalMethod */
        $type = new EventIdType();

        self::assertSame(EventIdType::NAME, $type->getName());
    }
}
