<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Env;
use Symfony\Config\FrameworkConfig;

return static function (ContainerConfigurator $container, FrameworkConfig $framework): void {
    $framework->router()
        ->utf8(true);

    if (Env::PROD === $container->env()) {
        $framework->router()
            ->strictRequirements(null);
    }
};
