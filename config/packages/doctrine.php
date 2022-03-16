<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Env;
use Symfony\Config\DoctrineConfig;
use Symfony\Config\FrameworkConfig;

return static function (ContainerConfigurator $container, FrameworkConfig $framework, DoctrineConfig $doctrine): void {
    $connection = $doctrine->dbal()->connection('default');
    $connection->url('%env(resolve:DATABASE_URL)%');

    $orm = $doctrine->orm();
    $orm->autoGenerateProxyClasses(true);

    $em = $orm->entityManager('default');

    if (Env::TEST === $container->env()) {
        // "TEST_TOKEN" is typically set by ParaTest
        $connection->dbnameSuffix('_test%env(default::TEST_TOKEN)%');
    }

    if (Env::PROD === $container->env()) {
        $orm->autoGenerateProxyClasses(false);
        $em->queryCacheDriver()->type('pool')->pool('doctrine.system_cache_pool');
        $em->resultCacheDriver()->type('pool')->pool('doctrine.result_cache_pool');

        $framework->cache()->pool('doctrine.system_cache_pool')->adapters(['cache.system']);
        $framework->cache()->pool('doctrine.result_cache_pool')->adapters(['cache.app']);
    }
};
