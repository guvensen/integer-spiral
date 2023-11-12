<?php

use \App\IntegerSpiralController as IntegerSpiralController;
use \Database\Database as Database;
use \Dotenv\Dotenv as Dotenv;

require __DIR__ . '/vendor/autoload.php';

Dotenv::createUnsafeImmutable(__DIR__ . '/')->load();

$database = new Database(
    $_ENV['DB_CONNECTION'],
    $_ENV['DB_HOST'],
    $_ENV['DB_PORT'],
    $_ENV['DB_DATABASE'],
    $_ENV['DB_USERNAME'],
    $_ENV['DB_PASSWORD']
);

if (strpos($_SERVER['REQUEST_URI'], "?")) {
    $requestUri = strstr($_SERVER['REQUEST_URI'], '?', true);
} else {
    $requestUri = $_SERVER['REQUEST_URI'];
}

$path = explode('/', $requestUri);
$query = $_SERVER['QUERY_STRING'];

if (!array_key_exists(1, $path) || $path[1] === '') {
    echo $_ENV['APP_NAME'] . ' | Version= ' . $_ENV['APP_VERSION'];
    exit;
}

$layoutController = new IntegerSpiralController($database);

switch ($path[1]) {
    case 'layout':
        $id = array_key_exists(2, $path) ? $path[2] : null;
        $method = $_SERVER['REQUEST_METHOD'];

        echo $layoutController->index($method, $query, $id);
        break;
    default:
        header("Content-type: application/json; charset=UTF-8");
        http_response_code(404);
        echo "Page not found.";
}


