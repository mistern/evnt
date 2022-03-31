<?php

declare(strict_types=1);

namespace App\Shared\Application\Query;

interface PaginationFactory
{
    public function create(): Pagination;
}
