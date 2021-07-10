<?php

namespace PhpMx\Conversation;

use BotMan\BotMan\BotMan;

class Ping implements ConversationInterface
{
    public function __construct()
    {
    }

    public function handleMessage(BotMan $bot)
    {
        $bot->reply('pong 🏓');
    }

    public function subscribe(BotMan $botman)
    {
        $botman->hears('ping', array($this, 'handleMessage'));
    }
}
