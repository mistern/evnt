<?php

declare(strict_types=1);

namespace App\Event\Application\Query;

use App\Event\Application\Query\Exception\EventDetailsNotFound;

interface DisplayEventDetails
{
    /**
     * @throws EventDetailsNotFound
     */
    public function display(string $slug): EventDetails;
}
