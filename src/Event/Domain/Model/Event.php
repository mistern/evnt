<?php

declare(strict_types=1);

namespace App\Event\Domain\Model;

use App\Shared\Domain\Model\Slug;

class Event
{
    /**
     * @psalm-pure
     */
    private function __construct(private EventId $id, private Name $name, private Slug $slug)
    {
    }

    /**
     * @psalm-pure
     */
    public static function register(EventId $id, Name $name, Slug $slug): self
    {
        return new self($id, $name, $slug);
    }

    public function getId(): EventId
    {
        return $this->id;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getSlug(): Slug
    {
        return $this->slug;
    }
}
