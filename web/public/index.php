<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/routes.php';

use App\Core\View;
use FastRoute\RouteCollector;

$view = new View();
$view::$viewPath = __DIR__ . "/../views";

$pdo = new PDO(
    'mysql:dbname=' . $_ENV['DATABASE_NAME'] . ';host=' . $_ENV['DATABASE_HOST'] . ';port=' . $_ENV['DATABASE_PORT'],
    $_ENV['DATABASE_USER'],
    $_ENV['DATABASE_PASSWORD'],
    [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
    ]
);

$whoops = new Whoops\Run();
if ($_ENV['APP_ENV'] == 'dev') {
    $whoops->pushHandler(new Whoops\Handler\PrettyPageHandler());
} else {
    $whoops->pushHandler(function ($e) {
        global $view;
        http_response_code(500);
        echo $view->render("errors/500.php");
    });
}
$whoops->register();


$dispatcher = FastRoute\simpleDispatcher(function (RouteCollector $r) {
    foreach (ROUTES as $route) {
        $r->addRoute($route[0], $route[1], $route[2]);
    }
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo $view->render("errors/404.php");
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];

        http_response_code(405);
        break;
    case FastRoute\Dispatcher::FOUND:
        /** @var class-string<Controller> $className */
        $className = $routeInfo[1][0];
        /** @var callable-string<Controller> $method */
        $method = $routeInfo[1][1];
        /** @var array<string, mixed> $vars */
        $vars = $routeInfo[2];

        $class = new $className($pdo, $view);
        $class->$method($vars);
        break;
}
