<?php

declare(strict_types=1);

namespace App\Tests\Unit\Event\Domain\Model;

use PHPUnit\Framework\TestCase;

use function App\Tests\Fixtures\Event\Domain\Model\aName;
use function App\Tests\Fixtures\Event\Domain\Model\anEvent;
use function App\Tests\Fixtures\Event\Domain\Model\anEventId;
use function App\Tests\Fixtures\Event\Domain\Model\aShortIntro;
use function aSlug;

final class EventTest extends TestCase
{
    public function testItRegistersWithProvidedId(): void
    {
        $id = anEventId()->build();
        $event = anEvent()->withId($id)->build();

        self::assertEquals($id, $event->getId(), 'Event was not registered with provided ID.');
    }

    public function testItRegistersWithProvidedSlug(): void
    {
        $slug = aSlug()->build();
        $event = anEvent()->withSlug($slug)->build();

        self::assertEquals($slug, $event->getSlug(), 'Event was not registered with provided Event Slug.');
    }

    public function testItRegistersWithProvidedName(): void
    {
        $name = aName()->build();
        $event = anEvent()->withName($name)->build();

        self::assertEquals($name, $event->getName(), 'Event was not registered with provided Event Name.');
    }

    public function testItRegistersWithProvidedShortIntro(): void
    {
        $shortIntro = aShortIntro()->build();
        $event = anEvent()->withShortIntro($shortIntro)->build();

        self::assertEquals(
            $shortIntro,
            $event->getShortIntro(),
            'Event was not registered with providedd Event Short Intro.'
        );
    }
}
