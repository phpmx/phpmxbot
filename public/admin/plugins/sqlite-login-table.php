<?php

/**
 * Authenticate a user from a login table
 * @author Erick Ruiz de Chavez, https://erickruizdechavez.com/
 * @link https://www.adminer.org/plugins/#use
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @license https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
 */
class SqliteLoginTable
{
    private $table_name;
    private $username_field;
    private $password_field;
    private $password;

    public function __construct($table_name = 'login', $username_field = 'username', $password_field = 'password')
    {
        $this->table_name = $table_name;
        $this->username_field = $username_field;
        $this->password_field = $password_field;
    }

    public function credentials()
    {
        $this->password = get_password();
        return array(SERVER, $_GET["username"], "");
    }

    public function login($login, $password)
    {
        $username = q($login);
        $connection = connection();
        $connection->select_db(DB);

        $sql = <<<SQL
			SELECT *
			FROM {$this->table_name}
			WHERE {$this->username_field} = {$username}
		SQL;

        $hash = $connection->result($sql, $this->password_field);

        return password_verify($this->password, $hash);
    }
}
