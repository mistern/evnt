<?php

declare(strict_types=1);

use App\Shared\Infrastructure\Symfony\Controller\Frontend\HomepageController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes): void {
    $routes->add('homepage', '/')->controller(HomepageController::class)->methods([Request::METHOD_GET]);
};
