<?php

namespace PhpMx\Conversation;

use BotMan\BotMan\BotMan;
use PhpMx\Services\Message;
use PhpMx\Services\Leaderboard as LeaderboardService;

class Leaderboard implements ConversationInterface
{
    private LeaderboardService $leaderboard;
    private Message $message;

    public function __construct(LeaderboardService $leaderboard, Message $message)
    {
        $this->leaderboard = $leaderboard;
        $this->message = $message;
    }

    public function __invoke(BotMan $bot)
    {
        $leaderboard = $this->leaderboard->getLeaderboard(10);
        $title = '*Top Leaderboard*';
        $bot->reply($title, $this->message->arrayToBlocks($leaderboard, $title));
    }

    public function subscribe(BotMan $botman)
    {
        $botman->hears('leaderboard', $this);
    }
}
