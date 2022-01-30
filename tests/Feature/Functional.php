<?php

namespace PhpMx\Tests\Feature;

use PhpMx\Factories\ContainerBuilderFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Dotenv\Dotenv;

class Functional extends TestCase
{
    protected ContainerBuilder $container;

    protected function setUp(): void
    {
        $dotenv = new Dotenv();
        $dotenv->load(__DIR__ . '/../../.env');
        $this->container = ContainerBuilderFactory::create();
        parent::setUp();
    }
}
