<?php

declare(strict_types=1);

namespace App\Tests\Integration\Event\Infrastructure\Db;

use App\Event\Application\Query\EventListItem;
use App\Event\Infrastructure\Db\PgSqlListEvents;
use App\Shared\Application\Query\Pagination;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class PgSqlListEventsTest extends KernelTestCase
{
    private ?Connection $connection = null;

    public function testItProvidesEmptyList(): void
    {
        $query = new PgSqlListEvents($this->getConnection());

        $pagination = $query->list(new Pagination(1, 1));

        self::assertCount(0, $pagination, 'Event listing did not provide empty list.');
    }

    /**
     * @throws Exception
     */
    public function testItPaginatesFirstPage(): void
    {
        $connection = $this->getConnection();
        $this->createRows([
            ['id' => $id = 'a5dff3c1-fc9a-4d68-921c-a5cd0c91185d', 'name' => $name = 'Event 1'],
            ['id' => '98b3c0a7-dc96-4ea8-b131-2f443b1972e4', 'name' => 'Event 2'],
            ['id' => 'b02da66b-366a-4aca-926e-37fc72d3cf00', 'name' => 'Event 3'],
        ]);
        $query = new PgSqlListEvents($connection);

        $pagination = $query->list(new Pagination(1, 2));

        /** @var array<EventListItem> $items */
        $items = [...$pagination->getCurrentPageResults()];
        self::assertCount(2, $items, 'Event listing did not provide 2 list items.');
        $firstItem = $items[0];
        self::assertSame($id, $firstItem->id, 'Event ID was not loaded.');
        self::assertSame($name, $firstItem->name, 'Event Name was not loaded.');
    }

    /**
     * @throws Exception
     */
    public function testItPaginatesSecondPage(): void
    {
        $connection = $this->getConnection();
        $this->createRows([
            ['id' => 'a5dff3c1-fc9a-4d68-921c-a5cd0c91185d', 'name' => 'Event 1'],
            ['id' => '98b3c0a7-dc96-4ea8-b131-2f443b1972e4', 'name' => 'Event 2'],
            ['id' => $id = 'b02da66b-366a-4aca-926e-37fc72d3cf00', 'name' => $name = 'Event 3'],
        ]);
        $query = new PgSqlListEvents($connection);

        $pagination = $query->list(new Pagination(2, 2));

        /** @var array<EventListItem> $items */
        $items = [...$pagination->getCurrentPageResults()];
        self::assertCount(1, $items, 'Event listing did not provide only 1 list item.');
        $firstItem = $items[0];
        self::assertSame($id, $firstItem->id, 'Event ID was not loaded.');
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

    /**
     * @param array<array{id: string, name: string}> $rows
     * @throws Exception
     */
    private function createRows(array $rows): void
    {
        $connection = $this->getConnection();
        foreach ($rows as $row) {
            $connection->executeQuery(
                'INSERT INTO events (id, name) VALUES (:id, :name)',
                ['id' => $row['id'], 'name' => $row['name']]
            );
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
