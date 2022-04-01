<?php

declare(strict_types=1);

use App\Event\Infrastructure\Symfony\Controller\Frontend\DetailsController;
use App\Event\Infrastructure\Symfony\Controller\Frontend\ListingController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes): void {
    $routes->add('listing', '/')
        ->controller(ListingController::class)
        ->methods([Request::METHOD_GET]);
    $routes->add('details', '/{slug}/')
        ->controller(DetailsController::class)
        ->methods([Request::METHOD_GET]);
};
