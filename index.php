<?php

use \App\IntegerSpiralController AS IntegerSpiralController;
use \Database\Database AS Database;
use \Dotenv\Dotenv AS Dotenv;

require __DIR__ . '/vendor/autoload.php';

header("Content-type: application/json; charset=UTF-8");

Dotenv::createUnsafeImmutable(__DIR__ . '/')->load();

$database = new Database(
    $_ENV['DB_CONNECTION'],
    $_ENV['DB_HOST'],
    $_ENV['DB_PORT'],
    $_ENV['DB_DATABASE'],
    $_ENV['DB_USERNAME'],
    $_ENV['DB_PASSWORD']
);

$path = explode('/', $_SERVER['REQUEST_URI']);

if(!array_key_exists(1, $path) || $path[1] === '' ){
    echo $_ENV['APP_NAME'] .' | Version= '.$_ENV['APP_VERSION'];
    exit;
}

switch ($path[1]){
    case 'layout':
        $id = array_key_exists(2, $path) ? $path[2] : null;
        $method = $_SERVER['REQUEST_METHOD'];

        $layoutController = new IntegerSpiralController($database);
        $layoutController->index($method, $id);

        break;
    default:
        http_response_code(404);
}


