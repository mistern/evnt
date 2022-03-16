<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Env;
use Symfony\Config\TwigConfig;

return static function (ContainerConfigurator $container, TwigConfig $twig): void {
    $twig->defaultPath('%kernel.project_dir%/templates');

    if (Env::TEST === $container->env()) {
        $twig->strictVariables(true);
    }
};
