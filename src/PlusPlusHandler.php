<?php

namespace PhpMx;

require_once __DIR__ . '/../constants.inc.php';

use SQLite3;

class PlusPlusHandler
{
    public static function tokenize($msg)
    {
        $msg = trim($msg);

        // Add spaces around ++ and -- just in case someone forgot to add them
        $msg = implode(' ++ ', explode('++', $msg));
        $msg = implode(' -- ', explode('--', $msg));

        // Remove all unknown characters
        $msg = preg_replace('/[^a-z0-9_@:#<>| \-+]/i', '', $msg);

        // Replace 1+ consecutive spaces with single commas
        $msg = preg_replace('/\s+/i', ',', $msg);

        // Break string into an array of tokens
        $msg = explode(',', $msg);

        $inc = [];
        $dec = [];
        $buffer = [];

        while ($token = array_shift($msg)) {
            if ($token !== '++' && $token !== '--') {
                $buffer[] = $token;
                continue;
            }

            if ($token === '++') {
                $inc = array_merge($inc, $buffer);
            } else {
                $dec = array_merge($dec, $buffer);
            }

            $buffer = [];
        }

        return [$inc, $dec];
    }

    public static function updatePoints($tokens)
    {
        $inc = $tokens[0];
        $dec = $tokens[1];

        $db = new SQLite3(DB);

        $sql = <<<SQL
		INSERT INTO leaderboard ( token, points )
			VALUES (:token, 1)
			ON CONFLICT (token)
			DO UPDATE SET points=points+1
		SQL;
        $query = $db->prepare($sql);

        foreach ($inc as $token) {
            $query->bindParam(':token', $token);
            $query->execute();
        }

        $sql = <<<SQL
		INSERT INTO leaderboard ( token, points )
			VALUES (:token, -1)
			ON CONFLICT (token)
			DO UPDATE SET points=points-1
		SQL;
        $query = $db->prepare($sql);

        foreach ($dec as $token) {
            $query->bindParam(':token', $token);
            $query->execute();
        }

        $tokens = array_merge([], $inc, $dec);
        $placeholders = implode(', ', array_map(fn($i) => ":token{$i}", range(0, count($tokens) - 1)));

        $sql = <<<SQL
		SELECT *
			FROM leaderboard
			WHERE token IN ( {$placeholders} )
		SQL;

        $query = $db->prepare($sql);
        foreach ($tokens as $i => $token) {
            $query->bindValue(":token{$i}", $token);
        }

        $result = $query->execute();
        $points = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $points[$row['token']] = $row['points'];
        }

        $db->close();

        return $points;
    }

    public static function leaderboard($count = 10)
    {
        $db = new SQLite3(DB);

        $sql = <<<SQL
		SELECT *
			FROM leaderboard
			ORDER BY points DESC
			LIMIT $count
		SQL;

        $result = $db->query($sql);
        $points = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $points[$row['token']] = $row['points'];
        }

        $db->close();

        return $points;
    }

    public static function reaction($event, $added = true)
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

        $tokens [$type][] = "<@{$event['item_user']}>";

        return self::updatePoints($tokens);
    }
}
