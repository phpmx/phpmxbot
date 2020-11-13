<?php

use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use BotMan\BotMan\Drivers\Tests\FakeDriver;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Dotenv\Dotenv;
use BotMan\BotMan\BotMan;
use PhpMx\Router;

require 'vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');

$containerBuilder = new ContainerBuilder();
$loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__ . '/config'));
$loader->load('services.yaml');
$containerBuilder->compile(true);
$botman = $containerBuilder->get(BotMan::class);

$faker = new FakeDriver();
$faker->messages = [
    new \BotMan\BotMan\Messages\Incoming\IncomingMessage('@wawa++', 'dmouse', 'all'),
    new \BotMan\BotMan\Messages\Incoming\IncomingMessage('@wawa--', 'dmouse', 'all'),
    new \BotMan\BotMan\Messages\Incoming\IncomingMessage('cosa @juan @wawa++', 'dmouse', 'all'),
    new \BotMan\BotMan\Messages\Incoming\IncomingMessage('(cosa @juan @wawa)++', 'dmouse', 'all'),
    new \BotMan\BotMan\Messages\Incoming\IncomingMessage('(cosa @juan @wawa)--', 'dmouse', 'all'),
    new \BotMan\BotMan\Messages\Incoming\IncomingMessage('leaderboard', 'dmouse', 'all'),
];
$botman->setDriver($faker);

$router = $containerBuilder->get(Router::class);
$router->mount();

$botman->listen();
