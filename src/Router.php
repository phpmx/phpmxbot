<?php

namespace PhpMx;

use BotMan\BotMan\BotMan;
use PhpMx\Conversation\ConversationInterface;
use Psr\Container\ContainerInterface;

class Router
{
    private $botman;
    private $container;

    public function __construct(BotMan $botman, ContainerInterface $container)
    {
        $this->botman = $botman;
        $this->container = $container;
    }

    public function mount(): void
    {
        /** @var Con $conversation */
        foreach ($this->container->findTaggedServiceIds('conversations') as $name => $c) {
            $conversation = $this->container->get($name);
            if ($conversation instanceof ConversationInterface) {
                $conversation->subscriber($this->botman);
            }
        }
    }

}
