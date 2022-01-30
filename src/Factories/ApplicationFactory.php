<?php

namespace PhpMx\Factories;

use PhpMx\Application\ApiApplication;
use PhpMx\Application\ApplicationInterface;
use PhpMx\Application\BotApplication;
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
            return $this->containerBuilder->get(ApiApplication::class);
        }

        return $this->containerBuilder->get(BotApplication::class);
    }
}