<?php

require_once __DIR__ . '/../constants.inc.php';

if ($argc !== 2) {
    die('Usage: php ' . __FILE__ . ' initial_admin_password');
}

$password = password_hash($argv[1], PASSWORD_DEFAULT);
$sql = str_replace('%%PASSWORD_HASH%%', $password, file_get_contents(DB_SQL));
$db = new SQLite3(DB);
$query = $db->exec($sql);
