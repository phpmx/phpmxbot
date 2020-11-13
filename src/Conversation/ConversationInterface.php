<?php

namespace PhpMx\Conversation;

use BotMan\BotMan\BotMan;

interface ConversationInterface
{
    public function subscriber(BotMan $botMan);
}