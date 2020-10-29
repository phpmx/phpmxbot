<?php

$config = parse_ini_file(__DIR__ . '/config.ini', true);

define('DRIVER', 'sqlite');
define('DB', __DIR__ . '/' . $config['db']['db']);
define('DB_SQL', __DIR__ . '/' . $config['db']['sql']);
