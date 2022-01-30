<?php

namespace PhpMx\Tests\Feature\Factories;

use PhpMx\Application\ApiApplication;
use PhpMx\Application\BotApplication;
use PhpMx\Factories\ApplicationFactory;
use PhpMx\Tests\Feature\Functional;

class ApplicationFactoryTest extends Functional
{
    public function testReturnBotApplication()
    {
        $result = $this->container->get(ApplicationFactory::class)
            ->createAppByUri('/');

        $this->assertInstanceOf(BotApplication::class, $result);
    }

    public function testReturnApiApplication()
    {
        $result = $this->container->get(ApplicationFactory::class)
            ->createAppByUri('/api/leaderboard');

        $this->assertInstanceOf(ApiApplication::class, $result);
    }
}
