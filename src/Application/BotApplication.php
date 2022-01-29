<?php

namespace PhpMx\Application;

use BotMan\BotMan\Drivers\DriverManager;
use PhpMx\Drivers\CustomDriver;
use BotMan\BotMan\BotMan;
use PhpMx\Router;

class BotApplication implements ApplicationInterface
{
    /** @var BotMan */
    private $botMan;

    /** @var Router */
    private $router;

    public function __construct(BotMan $botMan, Router $router)
    {
        $this->botMan = $botMan;
        $this->router = $router;
    }

    public function execute()
    {
        DriverManager::loadDriver(CustomDriver::class);
        $this->botMan->loadDriver('Slack');
        $this->router->mount();

        $this->botMan->listen();
    }
}