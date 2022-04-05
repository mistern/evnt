<?php

declare(strict_types=1);

namespace App\Event\Domain\Model;

use App\Shared\Domain\Model\Slug;

class Event
{
    /**
     * @psalm-pure
     */
    private function __construct(
        private EventId $id,
        private Slug $slug,
        private Name $name,
        private ShortIntro $shortIntro
    ) {
    }

    /**
     * @psalm-pure
     */
    public static function register(EventId $id, Slug $slug, Name $name, ShortIntro $shortIntro): self
    {
        return new self($id, $slug, $name, $shortIntro);
    }

    public function getId(): EventId
    {
        return $this->id;
    }

    public function getSlug(): Slug
    {
        return $this->slug;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getShortIntro(): ShortIntro
    {
        return $this->shortIntro;
    }
}
