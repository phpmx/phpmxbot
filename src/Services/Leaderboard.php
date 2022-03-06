<?php

namespace PhpMx\Services;

use SQLite3;

class Leaderboard
{
    private SQLite3 $db;
    private const REACTION_TARGET_USER = 'user';
    private const REACTION_TARGET_SELF = 'self';

    private array $knownReactions = [
        'heavy_plus_sign' => [self::REACTION_TARGET_USER, 1],
        'heavy_minus_sign' => [self::REACTION_TARGET_USER, -1],
        'middle_finger' => [self::REACTION_TARGET_SELF, -100],
    ];

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

    public function updatePoints($user, $tokens, $points = 1)
    {
        $inc = $tokens[0];
        $dec = $tokens[1];

        $this->logPoints($user, $inc, $points);
        $this->logPoints($user, $dec, $points);

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
        $type = 0;
        $modifier = $added ? 1 : -1;

        $reactionChange = $this->knownReactions[$event['reaction']];
        if (!$reactionChange) {
            return;
        }

        if ($modifier * $reactionChange[1] < 0) {
            $type = 1;
        }

        $user = $event['user'] ?? 'unknown';
        $targetUser = $reactionChange[0] === self::REACTION_TARGET_SELF ? $event['user'] : $event['item_user'];
        $tokens[$type][] = "<@{$targetUser}>";

        return $this->updatePoints($user, $tokens, $modifier * $reactionChange[1]);
    }
}
