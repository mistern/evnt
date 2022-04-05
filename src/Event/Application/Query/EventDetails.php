<?php

declare(strict_types=1);

namespace App\Event\Application\Query;

final class EventDetails
{
    public function __construct(public readonly string $name, public readonly string $shortIntro)
    {
    }
}
