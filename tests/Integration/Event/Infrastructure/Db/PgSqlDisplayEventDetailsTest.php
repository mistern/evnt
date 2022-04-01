<?php

declare(strict_types=1);

namespace App\Tests\Integration\Event\Infrastructure\Db;

use App\Event\Application\Query\Exception\EventDetailsNotFound;
use App\Event\Infrastructure\Db\PgSqlDisplayEventDetails;
use App\Tests\Functional\EventHelper;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use function App\Tests\Fixtures\Event\Domain\Model\anEvent;

final class PgSqlDisplayEventDetailsTest extends KernelTestCase
{
    use EventHelper;

    private ?Connection $connection = null;

    /**
     * @throws Exception
     */
    public function testItProvidesEventDetails(): void
    {
        $this->storeEvents(
            anEvent()->withSlug($slug = 'event-details-1')->withName($name = 'Event details 1'),
        );
        $query = new PgSqlDisplayEventDetails($this->getConnection());

        $eventDetails = $query->display($slug);

        self::assertSame($name, $eventDetails->name, 'Event Name was not loaded.');
    }

    /**
     * @throws Exception
     */
    public function testItFailsToProvideEventDetailsIfEventBySlugDoesNotExist(): void
    {
        $slug = 'non-existing-slug';
        $this->expectExceptionObject(EventDetailsNotFound::bySlug($slug));
        $query = new PgSqlDisplayEventDetails($this->getConnection());

        $query->display($slug);
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
