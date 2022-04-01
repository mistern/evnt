<?php

declare(strict_types=1);

namespace App\Tests\Integration\Event\Infrastructure\Db;

use App\Event\Application\Query\EventListItem;
use App\Event\Infrastructure\Db\PgSqlListEvents;
use App\Shared\Application\Query\Pagination;
use App\Tests\Functional\EventHelper;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use function App\Tests\Fixtures\Event\Domain\Model\anEvent;

final class PgSqlListEventsTest extends KernelTestCase
{
    use EventHelper;

    private ?Connection $connection = null;

    public function testItProvidesEmptyList(): void
    {
        $query = new PgSqlListEvents($this->getConnection());

        $pagination = $query->list(new Pagination(1, 1));

        self::assertCount(0, $pagination, 'Event listing did not provide empty list.');
    }

    public function testItPaginatesFirstPage(): void
    {
        $this->storeEvents(
            anEvent()
                ->withId('a5dff3c1-fc9a-4d68-921c-a5cd0c91185d')
                ->withSlug($slug = 'event-1')
                ->withName($name = 'Event 1'),
            anEvent()
                ->withId('98b3c0a7-dc96-4ea8-b131-2f443b1972e4')
                ->withSlug('event-2'),
            anEvent()
                ->withId('b02da66b-366a-4aca-926e-37fc72d3cf00')
                ->withSlug('event-3'),
        );
        $query = new PgSqlListEvents($this->getConnection());

        $pagination = $query->list(new Pagination(1, 2));

        /** @var array<EventListItem> $items */
        $items = [...$pagination->getCurrentPageResults()];
        self::assertCount(2, $items, 'Event listing did not provide 2 list items.');
        $firstItem = $items[0];
        self::assertSame($slug, $firstItem->slug, 'Event Slug was not loaded.');
        self::assertSame($name, $firstItem->name, 'Event Name was not loaded.');
    }

    public function testItPaginatesSecondPage(): void
    {
        $this->storeEvents(
            anEvent()
                ->withId('a5dff3c1-fc9a-4d68-921c-a5cd0c91185d')
                ->withSlug('event-1'),
            anEvent()
                ->withId('98b3c0a7-dc96-4ea8-b131-2f443b1972e4')
                ->withSlug('event-2'),
            anEvent()
                ->withId('b02da66b-366a-4aca-926e-37fc72d3cf00')
                ->withSlug($slug = 'event-3')
                ->withName($name = 'Event 3'),
        );
        $query = new PgSqlListEvents($this->getConnection());

        $pagination = $query->list(new Pagination(2, 2));

        /** @var array<EventListItem> $items */
        $items = [...$pagination->getCurrentPageResults()];
        self::assertCount(1, $items, 'Event listing did not provide only 1 list item.');
        $firstItem = $items[0];
        self::assertSame($slug, $firstItem->slug, 'Event Slug was not loaded.');
        self::assertSame($name, $firstItem->name, 'Event Name was not loaded.');
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        if (null !== $this->connection) {
            $this->connection->close();
            $this->connection = null;
        }
    }

    private function getConnection(): Connection
    {
        if (null === $this->connection) {
            $this->connection = self::bootKernel()->getContainer()->get('doctrine.dbal.default_connection');
        }

        return $this->connection;
    }
}
