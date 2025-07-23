<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/routes.php';
require_once __DIR__ . '/../config/error_handler.php';

use App\Core\View;
use App\Exception\MethodNotAllowedException;
use App\Exception\NotFoundException;
use App\Exception\Wrapper\ApiExceptionWrapper;
use App\Exception\Wrapper\WebExceptionWrapper;
use FastRoute\RouteCollector;

$view = new View();
$view::$viewPath = __DIR__ . "/../views";

$pdo = new PDO(
    'mysql:dbname=' . $_ENV['DATABASE_NAME'] . ';host=' . $_ENV['DATABASE_HOST'] . ';port=' . $_ENV['DATABASE_PORT'],
    $_ENV['DATABASE_USER'],
    $_ENV['DATABASE_PASSWORD'],
    [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
);

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
        throw new WebExceptionWrapper(new NotFoundException());
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];

        throw new ApiExceptionWrapper(new MethodNotAllowedException($allowedMethods));
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
