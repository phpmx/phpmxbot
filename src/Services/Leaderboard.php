<?php

namespace PhpMx\Services;

use SQLite3;

class Leaderboard
{
    private $db;

    public function __construct(SQLite3 $db)
    {
        $this->db = $db;
    }

    private function logPoints($user, $tokens, $points)
    {
        $sql = <<<SQL
        INSERT INTO leaderboard ( user, token, points, method, timestamp )
        VALUES (:user, :token, :points, :method, (SELECT strftime('%s', 'now')))
        SQL;

        $query = $this->db->prepare($sql);
        $query->bindValue(':user', $user);
        $query->bindValue(':points', $points);
        $query->bindValue(':method', 'message');

        foreach ($tokens as $token) {
            $query->bindParam(':token', $token);
            $query->execute();
        }
    }

    public function updatePoints($user, $tokens)
    {
        $inc = $tokens[0];
        $dec = $tokens[1];

        $this->logPoints($user, $inc, 1);
        $this->logPoints($user, $dec, -1);

        $tokens = [...$inc, ...$dec];
        $placeholders = implode(', ', array_map(fn($i) => ":token{$i}", range(0, count($tokens) - 1)));

        $sql = <<<SQL
		SELECT *
			FROM leaderboard_month
			WHERE token IN ( {$placeholders} )
		SQL;

        $query = $this->db->prepare($sql);
        foreach ($tokens as $i => $token) {
            $query->bindValue(":token{$i}", $token);
        }

        $result = $query->execute();
        $points = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $points[$row['token']] = $row['points'];
        }

        return $points;
    }

    public function getLeaderboard($count = 10)
    {
        $sql = <<<SQL
		SELECT *
			FROM leaderboard_month
			ORDER BY points DESC
			LIMIT $count
		SQL;

        $result = $this->db->query($sql);
        $points = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $points[$row['token']] = $row['points'];
        }

        return $points;
    }

    public function reaction($event, $added = true)
    {
        $tokens = [[], []];
        $type = -1;

        if (strpos($event['reaction'], '+1') !== false) {
            $type = $added ? 0 : 1;
        } elseif (strpos($event['reaction'], '-1') !== false) {
            $type = $added ? 1 : 0;
        }

        if ($type < 0) {
            return;
        }

        $user = $event['user'] ?? 'unknown';
        $tokens [$type][] = "<@{$event['item_user']}>";

        return $this->updatePoints($user, $tokens);
    }
}
