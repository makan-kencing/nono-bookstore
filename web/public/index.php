<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/routes.php';
require_once __DIR__ . '/../config/error_handler.php';

use App\Core\View;
use App\Exception\NotFoundException;
use App\Exception\Wrapper\WebExceptionWrapper;
use App\Router\Router;

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

$controllers = [
    'App\Controller\AdminUserController',
    'App\Controller\AuthorController',
    'App\Controller\BookController',
    'App\Controller\CartController',
    'App\Controller\CheckoutController',
    'App\Controller\HomeController',
    'App\Controller\OrderController',
    'App\Controller\PaymentController',
    'App\Controller\RatingController',
    'App\Controller\ReplyController',
    'App\Controller\SecurityController',
    'App\Controller\SeriesController',
    'App\Controller\UserProfileController',
    'App\Controller\WishlistController',
];
$router = new Router();
foreach ($controllers as $controller) {
    $router->registerController($controller);
}

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

assert(is_string($httpMethod));
assert(is_string($uri));

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

try {
    $route = $router->dispatch($httpMethod, $uri);
} catch (NotFoundException $e) {
    throw new WebExceptionWrapper($e);
}
$route->handle($uri, $pdo, $view);
