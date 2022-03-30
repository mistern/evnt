<?php

declare(strict_types=1);

namespace App\Event\Infrastructure\Db;

use App\Event\Application\Query\EventListItem;
use App\Event\Application\Query\ListEvents;
use App\Shared\Application\Query\Pagination;
use Doctrine\DBAL\Connection;
use Pagerfanta\Adapter\TransformingAdapter;
use Pagerfanta\Doctrine\DBAL\SingleTableQueryAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\PagerfantaInterface;

final class PgSqlListEvents implements ListEvents
{
    public function __construct(private Connection $connection)
    {
    }

    public function list(Pagination $pagination): PagerfantaInterface
    {
        $qb = $this->connection
            ->createQueryBuilder()
            ->select('e.id', 'e.name')
            ->from('events', 'e');
        /** @var SingleTableQueryAdapter<array{id: string, name: string}> $queryAdapter */
        $queryAdapter = new SingleTableQueryAdapter($qb, 'e.id');
        $adapter = new TransformingAdapter($queryAdapter, self::transform(...));

        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta
            ->setMaxPerPage($pagination->maxPerPage)
            ->setCurrentPage($pagination->currentPage);

        return $pagerfanta;
    }

    /**
     * @param array{id: string, name: string} $row
     */
    private static function transform(array $row): EventListItem
    {
        return new EventListItem($row['id'], $row['name']);
    }
}
