<?php

namespace PhpMx\Conversation;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use PhpMx\Services\Leaderboard;
use PhpMx\Services\Message;
use PhpMx\Services\Tokenizer;
use Tightenco\Collect\Support\Collection;

class PlusPlus implements ConversationInterface
{
    private $tokenizer;
    private $leaderboard;
    private $message;

    public function __construct(Tokenizer $tokenizer, Leaderboard $leaderboard, Message $message)
    {
        $this->tokenizer = $tokenizer;
        $this->leaderboard = $leaderboard;
        $this->message = $message;
    }

    public function handleMessage(BotMan $bot)
    {
        $msg = $bot->getMessage()->getText();
        $tokens = $this->tokenizer->getUsersFromMessage($msg);
        $payload = $bot->getMessage()->getPayload();

        $user = $payload['user'] ?? 'unknown';
        $points = $this->leaderboard->updatePoints($user, $tokens);

        $bot->replyInThread('Points updated!', $this->message->arrayToBlocks($points));
    }

    public function handleReactionAdded($payload, BotMan $bot)
    {
        $this->handleReactions($payload, $bot, true);
    }

    public function handleReactionRemoved($payload, BotMan $bot)
    {
        $this->handleReactions($payload, $bot, false);
    }

    public function handleReactions($payload, BotMan $bot, $added)
    {
        $event = json_decode($payload, true);
        $points = $this->leaderboard->reaction($event, $added);

        $matchingMessage = new IncomingMessage("", $event['item_user'], $event['item']['channel'], Collection::make($event['item']));
        $bot->replyInThread('Points updated!', $this->message->arrayToBlocks($points), $matchingMessage, $bot);
    }


    public function subscribe(BotMan $botman)
    {
        $botman->hears('.*(\+\+|\-\-).*', array($this, 'handleMessage'));
        $botman->on('reaction_added', array($this, 'handleReactionAdded'));
        $botman->on('reaction_removed', array($this, 'handleReactionRemoved'));
    }
}
