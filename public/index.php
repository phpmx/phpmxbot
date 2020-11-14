<?php

use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Slack\SlackDriver;
use Symfony\Component\Dotenv\Dotenv;
use BotMan\BotMan\BotMan;
use PhpMx\Router;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../.env');

$containerBuilder = new ContainerBuilder();
$loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__ . '/../config'));
$loader->load('services.yaml');
$containerBuilder->compile(true);

$botman = $containerBuilder->get(BotMan::class);
$router = $containerBuilder->get(Router::class);

DriverManager::loadDriver(SlackDriver::class);
$botman->loadDriver('Slack');
$router->mount();

$botman->listen();
