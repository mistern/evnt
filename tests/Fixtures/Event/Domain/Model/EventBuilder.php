<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\Event\Domain\Model;

use App\Event\Domain\Model\Event;
use App\Event\Domain\Model\EventId;
use App\Event\Domain\Model\Name;
use App\Event\Domain\Model\ShortIntro;
use App\Shared\Domain\Model\Slug;
use App\Tests\Fixtures\Shared\Domain\Model\SlugBuilder;

use function aSlug;
use function is_string;

/**
 * @psalm-immutable
 */
final class EventBuilder
{
    private EventId $id;
    private Slug $slug;
    private Name $name;
    private ShortIntro $shortIntro;

    public function __construct()
    {
        $this->id = anEventId()->build();
        $this->slug = aSlug()->build();
        $this->name = aName()->build();
        $this->shortIntro = aShortIntro()->build();
    }

    public function withId(string|EventIdBuilder|EventId $id): self
    {
        $new = clone $this;
        $new->id = match (true) {
            is_string($id) => EventId::fromString($id),
            $id instanceof EventIdBuilder => $id->build(),
            default => $id,
        };

        return $new;
    }

    public function withSlug(string|SlugBuilder|Slug $slug): self
    {
        $new = clone $this;
        $new->slug = match (true) {
            is_string($slug) => Slug::fromString($slug),
            $slug instanceof SlugBuilder => $slug->build(),
            default => $slug,
        };

        return $new;
    }

    public function withName(string|NameBuilder|Name $name): self
    {
        $new = clone $this;
        $new->name = match (true) {
            is_string($name) => Name::fromString($name),
            $name instanceof NameBuilder => $name->build(),
            default => $name,
        };

        return $new;
    }

    public function withShortIntro(string|ShortIntroBuilder|ShortIntro $shortIntro): self
    {
        $new = clone $this;
        $new->shortIntro = match (true) {
            is_string($shortIntro) => ShortIntro::fromString($shortIntro),
            $shortIntro instanceof ShortIntroBuilder => $shortIntro->build(),
            default => $shortIntro,
        };

        return $new;
    }


    public function build(): Event
    {
        return Event::register($this->id, $this->slug, $this->name, $this->shortIntro);
    }
}
