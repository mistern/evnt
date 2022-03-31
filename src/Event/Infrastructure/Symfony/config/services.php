<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Event\Application\Query\ListEvents;
use App\Event\Infrastructure\Db\PgSqlListEvents;

return static function (ContainerConfigurator $container): void {
    $container->import(__DIR__ . '/doctrine.php');

    $services = $container->services();
    $services->defaults()->autowire()->autoconfigure()->private();

    $services->load('App\Event\Infrastructure\Symfony\Controller\\', __DIR__ . '/../Controller');

    $services->set(PgSqlListEvents::class);
    $services->alias(ListEvents::class, PgSqlListEvents::class);
};
