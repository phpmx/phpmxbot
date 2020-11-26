<?php

namespace PhpMx\Conversation;

use BotMan\BotMan\BotMan;
use PhpMx\Services\Message;
use PhpMx\Services\Leaderboard as LeaderboardService;

class Leaderboard implements ConversationInterface
{
    private $leaderboard;
    private $message;

    public function __construct(LeaderboardService $leaderboard, Message $message)
    {
        $this->leaderboard = $leaderboard;
        $this->message = $message;
    }

    public function __invoke(BotMan $bot)
    {
        $leaderboard = $this->leaderboard->getLeaderboard(10);
        $bot->replyInThread('Top Leaderboard', $this->message->arrayToBlocks($leaderboard));
    }

    public function subscribe(BotMan $botman)
    {
        $botman->hears('leaderboard', $this);
    }
}
