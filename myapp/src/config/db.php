<?php

$config = [
    'host' => '172.27.0.1',
    'dbname' => 'paytest',
    'username' => 'bobby',
    'password' => 'tables',
    'port' => 33006,
];

if (file_exists(__DIR__ . '/db-local.php')) {
    $config = array_merge($config, require __DIR__ . '/db-local.php');
}

return $config;