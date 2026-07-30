<?php

declare(strict_types=1);

use App\Controllers\ArticleController;
use App\Controllers\CategoryController;
use App\Core\Database;
use App\Core\NotFoundException;
use App\Core\Router;
use App\Core\View;
use App\Controllers\HomeController;
use App\Repositories\ArticleRepository;
use App\Repositories\CategoryRepository;

$config = require __DIR__.'/../bootstrap.php';
$router = new Router();
$view = new View();

$router->get('/', function () use ($view, $config): string {
    $pdo = Database::getConnection($config);
    $categoryRepository = new CategoryRepository($pdo);
    $homeController = new HomeController($categoryRepository, $view, $config['latest_per_category']);
    return $homeController->index();
});

$router->get('/category/{id}', function (int $id) use ($view, $config): string {
    $pdo = Database::getConnection($config);

    return (new CategoryController(
        new CategoryRepository($pdo),
        new ArticleRepository($pdo),
        $view,
        $config['articles_per_page']
    ))->show($id);
});

$router->get('/article/{id}', function (int $id) use ($view, $config): string {
    $pdo = Database::getConnection($config);
    return (new ArticleController(
        new ArticleRepository($pdo),
        new CategoryRepository($pdo),
        $view,
        $config['similar_articles']
    ))->show($id);
});

try {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $method = strtoupper($_SERVER['REQUEST_METHOD']);
    echo $router->dispatch($method, $uri);
} catch (NotFoundException $e) {
    http_response_code(404);
    echo $view->render('errors/404.tpl');
} catch (Throwable $e) {
    http_response_code(500);
    if ($config['debug']) {
        echo '<pre>'.htmlspecialchars($e->getMessage()).'</pre>';
    }
}