<?php

declare(strict_types=1);

use App\Env;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes): void {
    if (Env::DEV === $routes->env()) {
        $routes
            ->import('@FrameworkBundle/Resources/config/routing/errors.xml')
            ->prefix('/_error');
    }
};
