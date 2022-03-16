<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Env;
use Symfony\Config\FrameworkConfig;

return static function (ContainerConfigurator $container, FrameworkConfig $framework): void {
    $framework
        ->secret('%env(APP_SECRET)%')
        ->httpMethodOverride(false);

    $framework->session()
        ->handlerId(null)
        ->cookieSecure('auto')
        ->cookieSamesite('lax')
        ->storageFactoryId('session.storage.factory.native');

    $framework->phpErrors()->log(true);

    if (Env::TEST === $container->env()) {
        $framework->test(true);
        $framework->session()
            ->storageFactoryId('session.storage.factory.mock_file');
    }
};
