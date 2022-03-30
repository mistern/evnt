<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Shared\Infrastructure\Doctrine\ORM\MigrationFix;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->autoconfigure()->autowire()->private();

    $services->set(MigrationFix::class)->tag('doctrine.event_subscriber');
};
