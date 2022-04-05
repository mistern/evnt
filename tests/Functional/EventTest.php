<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

use function App\Tests\Fixtures\Event\Domain\Model\anEvent;
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
        $this->storeEvents(
            anEvent()
                ->withId('5a33ad51-dd31-462a-9fc3-0c5f6e8b4bd7')
                ->withSlug($slug1 = 'event-1')
                ->withName($name1 = 'Event 1')
                ->withShortIntro($shortIntro1 = 'Short introduction 1.'),
            anEvent()
                ->withId('00bb049e-2818-4862-b9e5-f8f05aa878a7')
                ->withSlug('event-2'),
            anEvent()
                ->withId('48be1420-ddc1-4b23-b1ea-497983a840a8')
                ->withSlug('event-3')
                ->withName($name3 = 'Event 3'),
        );

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
