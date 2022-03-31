<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class HomepageTest extends WebTestCase
{
    public function testItRespondsWithSuccessfulResponse(): void
    {
        $client = self::createClient();

        $client->request('GET', '/');

        self::assertSame(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode(),
            'Homepage did not respond with HTTP OK.'
        );
        self::assertSelectorExists('nav.navbar a[href="/"]', 'Homepage should contain link to "Home".');
        self::assertSelectorExists('nav.navbar a[href="/events/"]', 'Homepage should contain link to "Events".');
    }
}
