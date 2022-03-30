<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Event\Infrastructure\Doctrine\DBAL\Types\EventIdType;
use App\Event\Infrastructure\Doctrine\DBAL\Types\NameType;
use Symfony\Config\DoctrineConfig;

return static function (DoctrineConfig $doctrine): void {
    $dbal = $doctrine->dbal();
    $dbal->type(EventIdType::NAME)->class(EventIdType::class);
    $dbal->type(NameType::NAME)->class(NameType::class);

    $doctrine->orm()->entityManager('default')->mapping('Event')
        ->isBundle(false)
        ->type('php')
        ->dir(__DIR__ . '/doctrine')
        ->prefix('App\Event\Domain\Model')
        ->alias('Event');
};
