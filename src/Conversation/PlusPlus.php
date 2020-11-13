<?php

namespace PhpMx\Conversation;

use BotMan\BotMan\BotMan;
use PhpMx\Handlers\Message;
use PhpMx\Handlers\PlusPlus as PlusPlusHandler;
use PhpMx\Services\Tokenizer;

class PlusPlus implements ConversationInterface
{
    protected $tokenizer;

    public function __construct(Tokenizer $tokenizer)
    {
        $this->tokenizer = $tokenizer;
    }

    public function __invoke(BotMan $bot)
    {
        $msg = $bot->getMessage()->getText();
        dump($this->tokenizer->getUsersFromMessage($msg));
    }

    public function subscriber(BotMan $botMan)
    {
        $botMan->hears('.*(\+\+|\-\-).*', $this);
    }
}