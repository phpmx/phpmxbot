<?php

namespace PhpMx\Factories;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

class ContainerBuilderFactory
{
    public static function create(): ContainerBuilder
    {
        $containerBuilder = new ContainerBuilder();
        $loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('services.yaml');
        $containerBuilder->compile(true);

        return $containerBuilder;
    }
}
