<?php

namespace PhpMx\Services;

use SQLite3;

class Greeter
{
    private $db;

    public function __construct(SQLite3 $db)
    {
        $this->db = $db;
    }

    public function getMessagesFor(string $channel)
    {
        $sql = <<<SQL
        SELECT *
        FROM greeter
        WHERE channel = :channel
        ORDER BY `order` ASC
        SQL;

        $query = $this->db->prepare($sql);
        $query->bindValue(':channel', $channel);

        $result = $query->execute();
        $messages = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $messages[] = $row;
        }

        return $messages;
    }
}
