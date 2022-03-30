<?php

declare(strict_types=1);

namespace App\Shared\Application\Query;

final class Pagination
{
    public function __construct(
        public readonly int $currentPage,
        public readonly int $maxPerPage
    ) {
    }
}
