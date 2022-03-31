<?php

declare(strict_types=1);

use ComposerUnused\ComposerUnused\Configuration\Configuration;
use ComposerUnused\ComposerUnused\Configuration\NamedFilter;
use Webmozart\Glob\Glob;

return static function (Configuration $config): Configuration {
    return $config
        ->addNamedFilter(NamedFilter::fromString('babdev/pagerfanta-bundle'))
        ->addNamedFilter(NamedFilter::fromString('doctrine/doctrine-migrations-bundle'))
        ->addNamedFilter(NamedFilter::fromString('ext-iconv'))
        ->addNamedFilter(// TODO: this should not be reported
            NamedFilter::fromString('pagerfanta/doctrine-dbal-adapter')
        )
        ->addNamedFilter(NamedFilter::fromString('pagerfanta/twig'))
        ->addNamedFilter(NamedFilter::fromString('symfony/dotenv'))
        ->addNamedFilter(NamedFilter::fromString('symfony/flex'))
        ->addNamedFilter(NamedFilter::fromString('symfony/monolog-bundle'))
        ->addNamedFilter(NamedFilter::fromString('symfony/runtime'))
        ->addNamedFilter(NamedFilter::fromString('symfony/yaml'))
        ->addNamedFilter(NamedFilter::fromString('symfony/proxy-manager-bridge'))
        ->addNamedFilter(NamedFilter::fromString('symfony/twig-bundle'))
        ->setAdditionalFilesFor('icanhazstring/composer-unused', [
            __FILE__,
            ...Glob::glob(__DIR__ . '/config/*.php'),
        ]);
};
