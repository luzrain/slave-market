<?php

namespace SlaveMarket;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    // Import configurators
    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->import('../config/{packages}/*.yaml');
        $container->import('../config/{packages}/' . $this->environment . '/*.yaml');
        $container->import('../config/services.yaml');
        $container->import('../config/{services}_' . $this->environment . '.yaml');
    }

    // Import routes
    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import('../config/routes.yaml');
        $routes->import('Modules/*/Api/Controller/*.php', 'annotation');
    }
}
