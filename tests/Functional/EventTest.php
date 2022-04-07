<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Tests\Fixtures\Event\Domain\Model\EventBuilder;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

use function App\Tests\Fixtures\Event\Domain\Model\anEvent;
use function App\Tests\Fixtures\Event\Domain\Model\anEventListOf;
use function sprintf;

final class EventTest extends WebTestCase
{
    use EventHelper;

    public function testListingPageRespondsWithSuccessfulResponse(): void
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
    public function testListingPageShowsEventListingWithPagination(): void
    {
        $client = self::createClient();
        $slug1 = 'event-1';
        $name1 = 'Event 1';
        $shortIntro1 = 'Short introduction 1.';
        $event1 = static fn(EventBuilder $event): EventBuilder => $event
            ->withSlug($slug1)
            ->withName($name1)
            ->withShortIntro($shortIntro1);

        $name3 = 'Event 3';
        $event3 = static fn(EventBuilder $event): EventBuilder => $event->withName($name3);
        $events = anEventListOf(3)
            ->withNthItem(1, $event1)
            ->withNthItem(3, $event3);
        $this->storeEvents(...$events->build());

        $crawler = $client->request('GET', '/events/');

        self::assertSelectorExists(
            '.events .event a[href="/events/' . $slug1 . '/"]',
            'List should contain Event item with link to details page.'
        );
        self::assertSelectorTextContains(
            '.events',
            $name1,
            sprintf('List should contain Event item with Name "%s".', $name1)
        );
        self::assertSelectorTextContains(
            '.events p',
            $shortIntro1,
            sprintf('List should contain Event Short Intro item for Name "%s".', $name1)
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

    public function testListingPageShowsEmptyListingWithoutPagination(): void
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

    public function testEventDetailsPageRespondsWithSuccessfulResponse(): void
    {
        $client = self::createClient();
        $this->storeEvents(
            anEvent()
                ->withName($name = 'Event details 1')
                ->withSlug($slug = 'event-details-1')
                ->withShortIntro($shortIntro = 'Short introduction 1.')
        );

        $client->request('GET', '/events/' . $slug . '/');

        self::assertSame(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode(),
            'Event details page did not respond with HTTP OK.'
        );
        self::assertSelectorTextContains('h2', $name, 'Event details page should contain header with Event Name.');
        self::assertSelectorTextContains(
            'p',
            $shortIntro,
            'Event details page should contain paragraph with Event Short Intro.'
        );
    }
}
