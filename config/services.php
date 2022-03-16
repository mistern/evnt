<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->autowire(true)->autoconfigure(true);

    $services->load('App\\', __DIR__ . '/../src/')->exclude([__DIR__ . '/../src/Kernel.php']);
};
