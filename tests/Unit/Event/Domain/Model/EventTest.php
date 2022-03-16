<?php

declare(strict_types=1);

namespace App\Tests\Unit\Event\Domain\Model;

use PHPUnit\Framework\TestCase;

use function App\Tests\Fixtures\Domain\Model\aName;
use function App\Tests\Fixtures\Domain\Model\anEvent;
use function App\Tests\Fixtures\Domain\Model\anEventId;

final class EventTest extends TestCase
{
    public function testItRegistersWithProvidedId(): void
    {
        $id = anEventId()->build();
        $event = anEvent()->withId($id)->build();

        self::assertTrue($id->equals($event->getId()), 'Event was not registered with provided ID.');
    }

    public function testItRegistersWithProvidedName(): void
    {
        $name = aName()->build();
        $event = anEvent()->withName($name)->build();

        self::assertTrue($name->equals($event->getName()), 'Event was not registered with provided Event Name.');
    }
}
