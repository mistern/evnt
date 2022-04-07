<?php

declare(strict_types=1);

namespace App\Tests\Integration\Event\Infrastructure\Db;

use App\Event\Domain\Model\EventId;
use App\Event\Domain\Service\Exception\EventIdGenerationFailed;
use App\Event\Domain\Service\Exception\EventNotFound;
use App\Event\Infrastructure\Db\PgSqlEventRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidFactoryInterface;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use function App\Tests\Fixtures\Event\Domain\Model\anEvent;
use function App\Tests\Fixtures\Event\Domain\Model\anEventId;

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
            ->withSlug($slug = 'slug-to-be-stored-1')
            ->withName($name = 'Event name to be stored')
            ->withShortIntro($shortIntro = 'Short introduction to be stored.')
            ->build();

        $repository->store($event);

        $row = $entityManager->getConnection()
            ->fetchAssociative('SELECT id, name, slug, short_intro FROM events WHERE id = ?', [$id]);
        self::assertNotFalse($row, 'Row was not stored.');
        self::assertSame($id, $row['id'], 'Event ID was not stored.');
        self::assertSame($name, $row['name'], 'Event Name was not stored.');
        self::assertSame($slug, $row['slug'], 'Event Slug was not stored.');
        self::assertSame($shortIntro, $row['short_intro'], 'Event Short Intro was not stored.');
    }

    /**
     * @throws Exception
     */
    public function testItFindsEventById(): void
    {
        $entityManager = $this->getEntityManager();
        $repository = $this->createRepository(entityManager: $entityManager);
        $repository->store(
            anEvent()
                ->withId($id = 'a5dff3c1-fc9a-4d68-921c-a5cd0c91185d')
                ->withSlug($slug = 'slug-to-be-loaded-1')
                ->withName($name = 'Event name to be loaded')
                ->withShortIntro($shortIntro = 'Short introduction to be loaded.')
                ->build()
        );
        $entityManager->clear();

        $event = $repository->getById(anEventId()->withId($id)->build());

        self::assertSame($id, $event->getId()->toString(), 'Event ID was not loaded.');
        self::assertSame($name, $event->getName()->toString(), 'Event Name was not loaded.');
        self::assertSame($slug, $event->getSlug()->toString(), 'Event Slug was not loaded.');
        self::assertSame($shortIntro, $event->getShortIntro()->toString(), 'Event Short Intro was not loaded.');
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
        $uuidFactory = $this->createMock(UuidFactoryInterface::class);
        $uuidFactory->method('uuid4')->willReturn(Uuid::fromString($expectedId->toString()));
        $repository = $this->createRepository(uuidFactory: $uuidFactory);

        $id = $repository->getNextId();

        self::assertTrue($expectedId->equals($id), 'Expected next Event ID was not generated.');
    }

    public function testItFailsToGenerateNextId(): void
    {
        $uuidFactory = $this->createMock(UuidFactoryInterface::class);
        $uuidFactory->method('uuid4')->willThrowException($exception = new RuntimeException());
        $repository = $this->createRepository(uuidFactory: $uuidFactory);
        $this->expectExceptionObject(EventIdGenerationFailed::unexpectedly($exception));

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
            $uuidFactory ?? $this->createMock(UuidFactoryInterface::class)
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
