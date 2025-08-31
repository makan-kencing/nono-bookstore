<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/error_handler.php';

use App\Core\View;
use App\Exception\WebException;
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
    'App\Controller\Api\AdminUserController',
    'App\Controller\Api\CartController',
    'App\Controller\Api\CheckoutController',
    'App\Controller\Api\PaymentController',
    'App\Controller\Api\RatingController',
    'App\Controller\Api\ReplyController',
    'App\Controller\Api\UserController',
    'App\Controller\Api\WishlistController',
    'App\Controller\Web\AdminUserController',
    'App\Controller\Web\AdminDashboardController',
    'App\Controller\Web\AdminProfileController',
    'App\Controller\Web\AuthorController',
    'App\Controller\Web\ProductController',
    'App\Controller\Web\CartController',
    'App\Controller\Web\CheckoutController',
    'App\Controller\Web\HomeController',
    'App\Controller\Web\OrderController',
    'App\Controller\Web\PaymentController',
    'App\Controller\Web\SecurityController',
    'App\Controller\Web\SeriesController',
    'App\Controller\Web\WishlistController',
];
$router = new Router($pdo, $view);
foreach ($controllers as $controller) {
    $router->register($controller);
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
    $router->dispatch($httpMethod, $uri);
} catch (WebException $e) {
    throw new WebExceptionWrapper($e);
}
