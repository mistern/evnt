<?php

declare(strict_types=1);

namespace App\Event\Infrastructure\Symfony\Controller\Frontend;

use App\Event\Application\Query\ListEvents;
use App\Shared\Application\Query\PaginationFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class ListingController extends AbstractController
{
    public function __construct(private ListEvents $listEvents, private PaginationFactory $paginationFactory)
    {
    }

    public function __invoke(): Response
    {
        $events = $this->listEvents->list($this->paginationFactory->create());

        return $this->render('frontend/events/listing.html.twig', ['events' => $events]);
    }
}
