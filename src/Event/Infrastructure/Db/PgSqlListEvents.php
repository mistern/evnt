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

/**
 * @phpstan-type Row = array{slug: string, name: string, short_intro: string}
 */
final class PgSqlListEvents implements ListEvents
{
    public function __construct(private Connection $connection)
    {
    }

    public function list(Pagination $pagination): PagerfantaInterface
    {
        $qb = $this->connection
            ->createQueryBuilder()
            ->select('e.slug', 'e.name', 'e.short_intro')
            ->from('events', 'e');
        /** @var SingleTableQueryAdapter<Row> $queryAdapter */
        $queryAdapter = new SingleTableQueryAdapter($qb, 'e.id');
        $adapter = new TransformingAdapter($queryAdapter, self::transform(...));

        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta
            ->setMaxPerPage($pagination->maxPerPage)
            ->setCurrentPage($pagination->currentPage);

        return $pagerfanta;
    }

    /**
     * @param Row $row
     */
    private static function transform(array $row): EventListItem
    {
        return new EventListItem($row['slug'], $row['name'], $row['short_intro']);
    }
}
