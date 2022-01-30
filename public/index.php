<?php

use Symfony\Component\Dotenv\Dotenv;
use PhpMx\Factories\ApplicationFactory;
use PhpMx\Factories\ContainerBuilderFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../.env');

$containerBuilder = ContainerBuilderFactory::create();

/** @var ApplicationFactory $applicationFactory */
$applicationFactory = $containerBuilder->get(ApplicationFactory::class);
$applicationFactory
    ->createAppByUri($_SERVER['REQUEST_URI'])
    ->execute();
