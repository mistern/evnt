<?php

declare(strict_types=1);

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes): void {
    $routes->import(__DIR__ . '/../src/Controller/', 'annotation');
    $routes->import(__DIR__ . '/../src/Kernel.php', 'annotation');
};
