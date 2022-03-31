<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $container): void {
    $container->parameters()
        ->set('app.pagination.max_per_page', 10);

    $container->import(__DIR__ . '/../src/Event/Infrastructure/Symfony/config/services.php');
    $container->import(__DIR__ . '/../src/Shared/Infrastructure/Symfony/config/services.php');

    $services = $container->services();
    $services->defaults()->autowire()->autoconfigure()->private();
};
