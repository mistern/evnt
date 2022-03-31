<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Symfony\Config\BabdevPagerfantaConfig;

return static function (BabdevPagerfantaConfig $pagerfanta): void {
    $pagerfanta
        ->defaultView('twitter_bootstrap5')
        ->defaultTwigTemplate('@BabDevPagerfanta/twitter_bootstrap5.html.twig');
};
