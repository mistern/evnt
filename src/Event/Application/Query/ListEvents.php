<?php

declare(strict_types=1);

namespace App\Event\Application\Query;

use App\Shared\Application\Query\Pagination;
use Pagerfanta\PagerfantaInterface;

interface ListEvents
{
    /**
     * @return PagerfantaInterface<EventListItem>
     */
    public function list(Pagination $pagination): PagerfantaInterface;
}
