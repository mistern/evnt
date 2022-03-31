<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Symfony\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class HomepageController extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('frontend/homepage.html.twig');
    }
}
