<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Shared\Infrastructure\Doctrine\DBAL\Types\SlugType;
use Symfony\Config\DoctrineConfig;

return static function (DoctrineConfig $doctrine): void {
    $dbal = $doctrine->dbal();
    $dbal->type(SlugType::NAME)->class(SlugType::class);
};
