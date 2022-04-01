<?php

declare(strict_types=1);

namespace App\Event\Infrastructure\Symfony\Controller\Frontend;

use App\Event\Application\Query\DisplayEventDetails;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class DetailsController extends AbstractController
{
    public function __construct(private DisplayEventDetails $displayEventDetails)
    {
    }

    public function __invoke(string $slug): Response
    {
        $event = $this->displayEventDetails->display($slug);

        return $this->render('frontend/events/details.html.twig', ['event' => $event]);
    }
}
