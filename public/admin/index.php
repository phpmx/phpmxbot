<?php

use Symfony\Component\Dotenv\Dotenv;

require_once __DIR__ . '/../../vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../../.env');

define('DRIVER', 'sqlite');
define('DB', __DIR__ . '/../../' . $_ENV['DB_FILE']);

function adminer_object()
{
    foreach (glob(__DIR__ . '/plugins/*.php') as $filename) {
        include_once $filename;
    }

    $plugins = array(
        new SqliteLoginTable(),
        new AdminerReadableDates(),
    );

    return new AdminerPlugin($plugins);
}

include __DIR__ . '/adminer-4.7.7-sqlite-en.php';
