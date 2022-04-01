<?php

declare(strict_types=1);

namespace App\Event\Infrastructure\Db;

use App\Event\Application\Query\DisplayEventDetails;
use App\Event\Application\Query\EventDetails;
use App\Event\Application\Query\Exception\EventDetailsNotFound;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

final class PgSqlDisplayEventDetails implements DisplayEventDetails
{
    public function __construct(private Connection $connection)
    {
    }

    /**
     * @throws Exception
     */
    public function display(string $slug): EventDetails
    {
        /** @var array{name: string}|false $row */
        $row = $this->connection->createQueryBuilder()
            ->select('e.name')
            ->from('events', 'e')
            ->where('e.slug = :slug')
            ->setParameter('slug', $slug)
            ->setMaxResults(1)
            ->fetchAssociative();
        if (false === $row) {
            throw EventDetailsNotFound::bySlug($slug);
        }

        return new EventDetails($row['name']);
    }
}
