<?php

declare(strict_types=1);

namespace App\Tests\Integration\Event\Infrastructure\Db;

use App\Event\Application\Query\EventListItem;
use App\Event\Infrastructure\Db\PgSqlListEvents;
use App\Shared\Application\Query\Pagination;
use App\Tests\Fixtures\Event\Domain\Model\EventBuilder;
use App\Tests\Functional\EventHelper;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use function App\Tests\Fixtures\Event\Domain\Model\anEventListOf;

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
        $slug = 'event-1';
        $name = 'Event 1';
        $shortIntro = 'Short introduction 1.';
        $event = static fn(EventBuilder $event): EventBuilder => $event
            ->withSlug($slug)
            ->withName($name)
            ->withShortIntro($shortIntro);
        $events = anEventListOf(3)
            ->withNthItem(1, $event);

        $this->storeEvents(...$events->build());
        $query = new PgSqlListEvents($this->getConnection());

        $pagination = $query->list(new Pagination(1, 2));

        /** @var array<EventListItem> $items */
        $items = [...$pagination->getCurrentPageResults()];
        self::assertCount(2, $items, 'Event listing did not provide 2 list items.');
        $firstItem = $items[0];
        self::assertSame($slug, $firstItem->slug, 'Event Slug was not loaded.');
        self::assertSame($name, $firstItem->name, 'Event Name was not loaded.');
        self::assertSame($shortIntro, $firstItem->shortIntro, 'Event Short Intro was not loaded.');
    }

    public function testItPaginatesSecondPage(): void
    {
        $slug = 'event-3';
        $events = anEventListOf(3)
            ->withNthItem(3, static fn(EventBuilder $event): EventBuilder => $event->withSlug($slug));

        $this->storeEvents(...$events->build());
        $query = new PgSqlListEvents($this->getConnection());

        $pagination = $query->list(new Pagination(2, 2));

        /** @var array<EventListItem> $items */
        $items = [...$pagination->getCurrentPageResults()];
        self::assertCount(1, $items, 'Event listing did not provide only 1 list item.');
        $firstItem = $items[0];
        self::assertSame($slug, $firstItem->slug, 'Event Slug was not loaded.');
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
