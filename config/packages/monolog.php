<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Env;
use Monolog\Logger;
use Psr\Log\LogLevel;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Config\MonologConfig;

return static function (ContainerConfigurator $container, MonologConfig $monolog): void {
    $monolog->channels(['deprecation']);

    if (Env::DEV === $container->env()) {
        $mainHandler = $monolog->handler('main')
            ->type('stream')
            ->path('%kernel.logs_dir%/%kernel.environment%.log')
            ->level(LogLevel::DEBUG);
        $mainHandler->channels()
            ->elements(['!event']);

        $consoleHandler = $monolog->handler('console')
            ->type('console')
            ->processPsr3Messages(false);
        $consoleHandler->channels()
            ->elements(['!event', '!doctrine', '!console']);
    }

    if (Env::TEST === $container->env()) {
        $mainHandler = $monolog->handler('main')
            ->type('fingers_crossed')
            ->actionLevel(Logger::ERROR)
            ->handler('nested');
        $mainHandler->excludedHttpCode()
            ->code(Response::HTTP_NOT_FOUND);
        $mainHandler->excludedHttpCode()
            ->code(Response::HTTP_METHOD_NOT_ALLOWED);
        $mainHandler->channels()
            ->elements(['!event']);

        $monolog->handler('nested')
            ->type('stream')
            ->path('%kernel.logs_dir%/%kernel.environment%.log')
            ->level(LogLevel::DEBUG);
    }

    if (Env::PROD === $container->env()) {
        $mainHandler = $monolog->handler('main')
            ->type('fingers_crossed')
            ->actionLevel(Logger::ERROR)
            ->handler('nested')
            ->bufferSize(50);
        $mainHandler->excludedHttpCode()
            ->code(Response::HTTP_NOT_FOUND);
        $mainHandler->excludedHttpCode()
            ->code(Response::HTTP_METHOD_NOT_ALLOWED);

        $monolog->handler('nested')
            ->type('stream')
            ->path('php://stderr')
            ->level(LogLevel::DEBUG)
            ->formatter('monolog.formatter.json');

        $consoleHandler = $monolog->handler('console')
            ->type('console')
            ->processPsr3Messages(false);
        $consoleHandler->channels()
            ->elements(['!event', '!doctrine']);

        $deprecationHandler = $monolog->handler('deprecation')
            ->type('stream')
            ->path('php://stderr');
        $deprecationHandler->channels()
            ->elements(['deprecation']);
    }
};
