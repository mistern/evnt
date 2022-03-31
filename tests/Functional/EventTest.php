<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

use function sprintf;

final class EventTest extends WebTestCase
{
    private ?Connection $connection = null;

    public function testItRespondsWithSuccessfulResponse(): void
    {
        $client = self::createClient();

        $client->request('GET', '/events/');

        self::assertSame(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode(),
            'Events listing page did not respond with HTTP OK.'
        );
    }

    /**
     * @throws Exception
     */
    public function testItShowsEventListingWithPagination(): void
    {
        $client = self::createClient();
        $this->createRows([
            ['id' => 'a5dff3c1-fc9a-4d68-921c-a5cd0c91185d', 'name' => $name1 = 'Event 1'],
            ['id' => '98b3c0a7-dc96-4ea8-b131-2f443b1972e4', 'name' => 'Event 2'],
            ['id' => 'b02da66b-366a-4aca-926e-37fc72d3cf00', 'name' => $name3 = 'Event 3'],
        ]);

        $crawler = $client->request('GET', '/events/');

        self::assertSelectorTextContains(
            '.events',
            $name1,
            sprintf('List should contain Event Name item with name "%s".', $name1)
        );
        self::assertSelectorExists('.events-pagination', 'List should contain pagination.');

        $nextLink = $crawler->filter('.events-pagination .page-link[rel=next]');
        self::assertCount(1, $nextLink, 'List should contain a link for next page.');
        $client->click($nextLink->link());

        self::assertSelectorTextContains(
            '.events',
            $name3,
            sprintf('List should contain Event Name item with name "%s".', $name3)
        );
    }

    public function testItShowsEmptyListingWithoutPagination(): void
    {
        $client = self::createClient();

        $crawler = $client->request('GET', '/events/');

        self::assertSame(
            0,
            $crawler->filter('.events .event')->count(),
            'List should not contain any Events.'
        );
        self::assertSelectorNotExists('.events-pagination', 'List should not contain pagination.');
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
