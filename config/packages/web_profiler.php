<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Env;
use Symfony\Config\FrameworkConfig;
use Symfony\Config\WebProfilerConfig;

return static function (
    ContainerConfigurator $container,
    FrameworkConfig $framework,
    WebProfilerConfig $webProfiler
): void {
    if (Env::DEV === $container->env()) {
        $webProfiler->toolbar(true)->interceptRedirects(false);
        $framework->profiler()->onlyExceptions(false);
    }

    if (Env::TEST === $container->env()) {
        $webProfiler->toolbar(false)->interceptRedirects(false);
        $framework->profiler()->collect(false);
    }
};
