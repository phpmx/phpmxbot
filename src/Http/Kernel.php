<?php

namespace PhpMx\Http;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

/**
 * kernel based on https://symfony.com/doc/current/configuration/micro_kernel_trait.html
 */
class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->add('api.leaderboard', '/api/leaderboard')
            ->controller('PhpMx\Http\Api\Controller\LeaderBoardController');
    }

    public function registerBundles()
    {
        return [
            new FrameworkBundle(),
        ];
    }

    protected function configureContainer(ContainerConfigurator $c): void
    {
        // PHP equivalent of config/packages/framework.yaml
        $c->extension('framework', [
            'secret' => 'S0ME_SECRET'
        ]);
    }
}
