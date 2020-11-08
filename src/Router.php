<?php

namespace PhpMx;

use BotMan\BotMan\BotMan;
use PhpMx\Interfaces\Route;

class Router
{
    public static function route(BotMan $botman)
    {
        // Autoload Routes
        $handle = dir(__DIR__ . '/Routes');
        while ($item = $handle->read()) {
            if (substr($item, -4) === '.php') {
                $class = substr($item, 0, -4);
                $class = __NAMESPACE__ . "\\Routes\\{$class}";

                $route = new $class($botman);
                if ($route instanceof Route) {
                    $route->init();
                }
            }
        }
    }
}
