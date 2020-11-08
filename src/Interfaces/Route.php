<?php

namespace PhpMx\Interfaces;

use BotMan\BotMan\BotMan;

interface Route
{
    public function __construct(BotMan $botman);
    public function init();
}
