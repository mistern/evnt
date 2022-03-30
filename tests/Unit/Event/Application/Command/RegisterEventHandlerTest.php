<?php

declare(strict_types=1);

namespace App\Tests\Unit\Event\Application\Command;

use App\Event\Application\Command\RegisterEvent;
use App\Event\Application\Command\RegisterEventHandler;
use App\Event\Domain\Service\EventRepository;
use App\Tests\Doubles\Event\InMemoryEventRepository;
use PHPUnit\Framework\TestCase;

use function App\Tests\Fixtures\Domain\Model\aName;
use function App\Tests\Fixtures\Domain\Model\anEventId;

final class RegisterEventHandlerTest extends TestCase
{
    public function testItRegistersEventWithProvidedId(): void
    {
        $id = anEventId()->withId('97dd4194-e7f0-4394-9e31-5aeaef92c692')->build();
        $command = self::createCommand($id->toString());
        $handler = new RegisterEventHandler($repository = self::createRepository());

        $handler($command);

        $event = $repository->getById($id);
        self::assertEquals($id, $event->getId(), 'Event was not registered with provided ID.');
    }

    public function testItRegistersEventWithProvidedName(): void
    {
        $name = aName()->withName('Provided event name')->build();
        $command = self::createCommand(name: $name->toString());
        $handler = new RegisterEventHandler($repository = self::createRepository());

        $handler($command);

        $event = $repository->getById(anEventId()->withId($command->id)->build());
        self::assertEquals($name, $event->getName(), 'Event was not registered with provided name.');
    }

    private static function createRepository(): EventRepository
    {
        return new InMemoryEventRepository();
    }

    private static function createCommand(?string $id = null, ?string $name = null): RegisterEvent
    {
        return new RegisterEvent($id ?? anEventId()->build()->toString(), $name ?? aName()->build()->toString());
    }
}
