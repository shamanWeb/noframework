<?php


use App\core\App;
use App\core\AppException;

define('ROOT_PATH', __DIR__ . '/../');
define('APP_PATH', ROOT_PATH . '/src/');

require_once ROOT_PATH . '/vendor/autoload.php';

$config = require ROOT_PATH . '/src/config/main.php';
try {
    (new App($config))->run();
} catch (AppException $e) {
    var_dump($e);
}