<?php

require_once __DIR__ . '/../constants.inc.php';

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
