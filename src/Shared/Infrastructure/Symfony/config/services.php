<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Shared\Application\Query\PaginationFactory;
use App\Shared\Infrastructure\Doctrine\ORM\MigrationFix;
use App\Shared\Infrastructure\HttpPaginationFactory;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->autoconfigure()->autowire()->private();

    $services->load('App\Shared\Infrastructure\Symfony\Controller\\', __DIR__ . '/../Controller');

    $services->set(MigrationFix::class)
        ->tag('doctrine.event_subscriber');

    $services->set(HttpPaginationFactory::class)
        ->arg('$maxPerPage', '%app.pagination.max_per_page%');
    $services->alias(PaginationFactory::class, HttpPaginationFactory::class);
};
