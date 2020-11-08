<?php

namespace PhpMx\Routes;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use PhpMx\Handlers\Message;
use PhpMx\Handlers\PlusPlus as PlusPlusHandler;
use PhpMx\Interfaces\Route;
use Tightenco\Collect\Support\Collection;

class PlusPlus implements Route
{
    private BotMan $botman;

    public function __construct(BotMan $botman)
    {
        $this->botman = $botman;
    }

    public function init()
    {
        $this->botman->hears('.*(\+\+|\-\-).*', function (BotMan $bot) {
            $msg = $bot->getMessage()->getText();
            $tokens = PlusPlusHandler::tokenize($msg);

            $payload = $bot->getMessage()->getPayload();
            $user = $payload['user'] ?? 'unknown';
            $points = PlusPlusHandler::updatePoints($user, $tokens);

            $bot->replyInThread('Points updated!', Message::arrayToBlocks($points));
        });

        $this->botman->hears('leaderboard', function (BotMan $bot) {
            $count = 10;
            $points = PlusPlusHandler::leaderboard($count);

            $bot->replyInThread('Top Leaderboard', Message::arrayToBlocks($points));
        });

        $this->botman->on('reaction_added', function ($payload, BotMan $bot) {
            $this->handleReaction($payload, $bot, true);
        });

        $this->botman->on('reaction_removed', function ($payload, BotMan $bot) {
            $this->handleReaction($payload, $bot, false);
        });
    }

    private function handleReaction($payload, BotMan $bot, $added)
    {
        $event = json_decode($payload, true);
        $points = PlusPlusHandler::reaction($event, $added);

        $matchingMessage = new IncomingMessage("", $event['item_user'], $event['item']['channel'], Collection::make($event['item']));
        $bot->replyInThread('Points updated!', Message::arrayToBlocks($points), $matchingMessage, $bot);
    }
}
