<?php

require_once __DIR__ . '/../vendor/autoload.php';

use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Slack\SlackDriver;
use PhpMx\Router;

DriverManager::loadDriver(SlackDriver::class);

$config = parse_ini_file(__DIR__ . '/../config.ini', true);
$botman = BotManFactory::create($config);

Router::route($botman);

$botman->listen();
