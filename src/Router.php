<?php

namespace PhpMx;

use BotMan\BotMan\BotMan;
use PhpMx\Conversation\ConversationInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\TaggedContainerInterface;

class Router
{
    private $botman;

    /** @var TaggedContainerInterface $container */
    private $container;

    public function __construct(BotMan $botman, ContainerInterface $container)
    {
        $this->botman = $botman;
        $this->container = $container;
    }

    public function mount(): void
    {
        foreach ($this->container->findTaggedServiceIds('conversations') as $name => $_) {
            $conversation = $this->container->get($name);
            if ($conversation instanceof ConversationInterface) {
                $conversation->subscribe($this->botman);
            }
        }
    }
}
