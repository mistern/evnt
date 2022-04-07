<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\Event\Domain\Model;

use App\Event\Domain\Model\Event;
use Ramsey\Uuid\Uuid;

use function sprintf;

/**
 * @psalm-immutable
 */
final class EventListBuilder
{
    private int $noItems;
    private EventBuilder $base;
    /**
     * @var array<int, pure-callable(EventBuilder): EventBuilder>
     * @noinspection PhpUndefinedClassInspection
     */
    private array $itemModifiers = [];

    public function __construct(int $noItems)
    {
        $this->noItems = $noItems;
        $this->base = anEvent();
    }

    public function noItems(int $noItems): self
    {
        $new = clone $this;
        $new->noItems = $noItems;

        return $new;
    }

    /**
     * @param pure-callable(EventBuilder): EventBuilder $eventModifier
     * @noinspection PhpUndefinedClassInspection
     * @noinspection PhpDocSignatureInspection
     */
    public function withNthItem(int $number, callable $eventModifier): self
    {
        $new = clone $this;
        $new->itemModifiers[$number] = $eventModifier;

        return $new;
    }

    /**
     * @return list<Event>
     */
    public function build(): array
    {
        /** @var list<Event> $events */
        $events = [];
        for ($n = 1; $n <= $this->noItems; ++$n) {
            $id = Uuid::fromInteger((string)$n)->toString();
            $slug = sprintf('event-%02d', $n);
            $name = sprintf('Event %02d', $n);
            $shortIntro = sprintf('Short introduction of "Event %02d".', $n);

            $event = $this->base
                ->withId($id)
                ->withSlug($slug)
                ->withName($name)
                ->withShortIntro($shortIntro);
            if (isset($this->itemModifiers[$n])) {
                $event = $this->itemModifiers[$n]($event);
            }

            $events[] = $event->build();
        }

        return $events;
    }
}
