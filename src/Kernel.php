<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    private function configureContainer(
        ContainerConfigurator $container,
        LoaderInterface $loader,
        ContainerBuilder $builder
    ): void {
        $configDir = $this->getConfigDir();

        $container->import($configDir . '/{packages}/*.{yaml,php}');
        $container->import($configDir . '/{packages}/*.local.{yaml,php}');
        $container->import($configDir . '/services.php');
        $container->import($configDir . '/services_' . $this->environment . '.php', ignoreErrors: 'not_found');
        $container->import($configDir . '/services.local.php', ignoreErrors: 'not_found');
        $container->import($configDir . '/services_' . $this->environment . '.local.php', ignoreErrors: 'not_found');
    }

    private function configureRoutes(RoutingConfigurator $routes): void
    {
        $configDir = $this->getConfigDir();

        $routes->import($configDir . '/{routes}/' . $this->environment . '/*.php');
        $routes->import($configDir . '/{routes}/*.php');
        $routes->import($configDir . '/{routes}.php');
    }
}
