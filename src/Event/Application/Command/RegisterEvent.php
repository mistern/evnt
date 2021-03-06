<?php

declare(strict_types=1);

namespace App\Event\Application\Command;

final class RegisterEvent
{
    public function __construct(
        public readonly string $id,
        public readonly string $slug,
        public readonly string $name,
        public readonly string $shortIntro
    ) {
    }
}
