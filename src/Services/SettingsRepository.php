<?php

namespace PhpMx\Services;

use SQLite3;

class SettingsRepository
{
    private SQLite3 $db;

    public function __construct(SQLite3 $db)
    {
        $this->db = $db;
    }

    /**
     * @return string[]
     */
    public function getJsonSetting(string $key): array
    {
        $query = <<<SQL
            SELECT value 
            FROM settings
            WHERE key = :key
        SQL;

        $statement = $this->db->prepare($query);
        $statement->bindValue(':key', $key);
        $row = $statement->execute()->fetchArray();

        return json_decode($row['value']);
    }
}
