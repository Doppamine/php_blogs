<?php

use App\Core\Router;
use App\Core\NotFoundException;

$config = require __DIR__ . '/../bootstrap.php';
$router = new Router();
$router->get('/', function (): string {
    return '<h1>Home Page</h1>';
});

try {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $method = strtoupper($_SERVER['REQUEST_METHOD']);
    echo $router->dispatch($method, $uri);
} catch (NotFoundException $e) {
    http_response_code(404);
    echo $e->getMessage();
} catch (Throwable $e) {
    http_response_code(500);
    echo $e->getMessage();
}