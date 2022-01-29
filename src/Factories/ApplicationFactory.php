<?php

namespace PhpMx\Factories;

use BotMan\BotMan\BotMan;
use PhpMx\Application\ApiApplication;
use PhpMx\Application\ApplicationInterface;
use PhpMx\Application\BotApplication;
use PhpMx\Router;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ApplicationFactory
{
    private ContainerBuilder $containerBuilder;

    public function __construct(ContainerBuilder $containerBuilder)
    {
        $this->containerBuilder = $containerBuilder;
    }

    public function createAppByUri(string $uri): ApplicationInterface
    {
        if (str_starts_with($uri, '/api')) {
            return new ApiApplication();
        }

        /** @var BotMan $botman */
        $botman = $this->containerBuilder->get(BotMan::class);

        /** @var Router $router */
        $router = $this->containerBuilder->get(Router::class);
        return new BotApplication($botman, $router);
    }
}