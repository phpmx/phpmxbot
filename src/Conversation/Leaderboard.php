<?php

namespace PhpMx\Conversation;

use BotMan\BotMan\BotMan;
use SQLite3;

class Leaderboard implements ConversationInterface
{
    private $db;

    public function __construct(SQLite3 $db)
    {
        $this->db = $db;
    }

    public function __invoke(BotMan $botman)
    {
        $sql = <<<SQL
		SELECT *
			FROM leaderboard_month
			ORDER BY points DESC
			LIMIT 10
		SQL;

        $result = $this->db->query($sql);
        $points = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $points[$row['token']] = $row['points'];
        }
        $this->db->close();

        return $points;
    }

    public function subscriber(BotMan $botMan)
    {
        $botMan->hears('leaderboard', $this);
    }
}