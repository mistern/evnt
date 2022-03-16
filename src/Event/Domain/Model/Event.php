<?php

declare(strict_types=1);

namespace App\Event\Domain\Model;

class Event
{
    /**
     * @psalm-pure
     */
    private function __construct(private EventId $id, private Name $name)
    {
    }

    /**
     * @psalm-pure
     */
    public static function register(EventId $id, Name $name): self
    {
        return new self($id, $name);
    }

    public function getId(): EventId
    {
        return $this->id;
    }

    public function getName(): Name
    {
        return $this->name;
    }
}
