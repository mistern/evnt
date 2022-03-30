<?php

declare(strict_types=1);

namespace App\Tests\Integration\Event\Infrastructure\Db;

use App\Event\Domain\Model\EventId;
use App\Event\Domain\Service\Exception\EventIdGenerationFailed;
use App\Event\Domain\Service\Exception\EventNotFound;
use App\Event\Infrastructure\Db\PgSqlEventRepository;
use App\Tests\Doubles\RamseyUuid\UuidFactoryDummy;
use App\Tests\Doubles\RamseyUuid\UuidFactoryStub;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidFactoryInterface;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use function App\Tests\Fixtures\Domain\Model\anEvent;
use function App\Tests\Fixtures\Domain\Model\anEventId;

final class PgSqlEventRepositoryTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager = null;

    /**
     * @throws Exception
     */
    public function testItStoresEvent(): void
    {
        $entityManager = $this->getEntityManager();
        $repository = $this->createRepository();
        $event = anEvent()
            ->withId($id = '123f3091-c3ad-4fb5-bb0d-c08aef9aea4b')
            ->withName($name = 'Event name to be stored')
            ->build();

        $repository->store($event);

        $row = $entityManager->getConnection()->fetchAssociative('SELECT id, name FROM events WHERE id = ?', [$id]);
        self::assertNotFalse($row, 'Row was not stored.');
        self::assertSame($id, $row['id'], 'Event ID was not stored.');
        self::assertSame($name, $row['name'], 'Event Name was not stored.');
    }

    /**
     * @throws Exception
     */
    public function testItFindsEventById(): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->getConnection()->executeQuery('INSERT INTO events (id, name) VALUES (:id, :name)', [
            'id' => $id = 'a5dff3c1-fc9a-4d68-921c-a5cd0c91185d',
            'name' => $name = 'Event name to be loaded',
        ]);
        $repository = $this->createRepository(entityManager: $entityManager);

        $event = $repository->getById(anEventId()->withId($id)->build());

        self::assertSame($id, $event->getId()->toString(), 'Event ID was not loaded');
        self::assertSame($name, $event->getName()->toString(), 'Event Name was not loaded');
    }

    public function testItFailsToFindEventByIdIfIdDoesNotExist(): void
    {
        $this->expectExceptionObject(EventNotFound::byId($id = anEventId()->build()));
        $repository = $this->createRepository();

        $repository->getById($id);
    }

    public function testItGeneratesNextId(): void
    {
        $expectedId = EventId::fromString('7a87d4bb-f041-4d15-88c7-35479a634207');
        $uuidFactory = new UuidFactoryStub();
        $uuidFactory->uuid4 = Uuid::fromString($expectedId->toString());
        $repository = $this->createRepository(uuidFactory: $uuidFactory);

        $id = $repository->getNextId();

        self::assertTrue($expectedId->equals($id), 'Expected next Event ID was not generated.');
    }

    public function testItFailsToGenerateNextId(): void
    {
        $uuidFactory = new UuidFactoryStub();
        $uuidFactory->uuid4 = new RuntimeException();
        $repository = $this->createRepository(uuidFactory: $uuidFactory);
        $this->expectExceptionObject(EventIdGenerationFailed::unexpectedly($uuidFactory->uuid4));

        $repository->getNextId();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        if (null !== $this->entityManager) {
            $this->entityManager->close();
            $this->entityManager = null;
        }
    }

    private function createRepository(
        ?EntityManagerInterface $entityManager = null,
        ?UuidFactoryInterface $uuidFactory = null
    ): PgSqlEventRepository {
        return new PgSqlEventRepository(
            $entityManager ?? $this->getEntityManager(),
            $uuidFactory ?? new UuidFactoryDummy()
        );
    }

    private function getEntityManager(): EntityManagerInterface
    {
        if (null === $this->entityManager) {
            $this->entityManager = self::bootKernel()->getContainer()->get('doctrine.orm.default_entity_manager');
        }

        return $this->entityManager;
    }
}
