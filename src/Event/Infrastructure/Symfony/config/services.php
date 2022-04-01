<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Env;
use App\Event\Application\Query\DisplayEventDetails;
use App\Event\Application\Query\ListEvents;
use App\Event\Domain\Service\EventRepository;
use App\Event\Infrastructure\Db\PgSqlDisplayEventDetails;
use App\Event\Infrastructure\Db\PgSqlEventRepository;
use App\Event\Infrastructure\Db\PgSqlListEvents;

return static function (ContainerConfigurator $container): void {
    $container->import(__DIR__ . '/doctrine.php');

    $services = $container->services();
    $services->defaults()->autowire()->autoconfigure()->private();

    $services->load('App\Event\Infrastructure\Symfony\Controller\\', __DIR__ . '/../Controller');

    $services->set(PgSqlListEvents::class);
    $services->alias(ListEvents::class, PgSqlListEvents::class);

    $services->set(PgSqlDisplayEventDetails::class);
    $services->alias(DisplayEventDetails::class, PgSqlDisplayEventDetails::class);

    $services->set(PgSqlEventRepository::class);
    $eventRepositoryService = $services->alias(EventRepository::class, PgSqlEventRepository::class);
    if (Env::TEST === $container->env()) {
        $eventRepositoryService->public();
    }
};
