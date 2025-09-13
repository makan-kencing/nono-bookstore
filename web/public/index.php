<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/error_handler.php';

use App\Core\View;
use App\Exception\WebException;
use App\Exception\Wrapper\WebExceptionWrapper;
use App\Router\Router;

date_default_timezone_set(getenv('TIMEZONE'));

$view = new View();
$view::$viewPath = __DIR__ . "/../views";

$pdo = new PDO(
    'mysql:dbname=' . getenv('DATABASE_NAME') . ';host=' . getenv('DATABASE_HOST') . ';port=' . getenv('DATABASE_PORT'),
    getenv('DATABASE_USER') ?: throw new RuntimeException('DATABASE_USER not defined'),
    getenv('DATABASE_PASSWORD') ?: throw new RuntimeException('DATABASE_PASSWORD not defined'),
    [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
);

$controllers = [
    'App\Controller\Api\AddressController',
    'App\Controller\Api\AdminOrderController',
    'App\Controller\Api\AdminUserController',
    'App\Controller\Api\AuthController',
    'App\Controller\Api\AuthorController',
    'App\Controller\Api\BookController',
    'App\Controller\Api\CartController',
    'App\Controller\Api\CheckoutController',
    'App\Controller\Api\FileController',
    'App\Controller\Api\PaymentController',
    'App\Controller\Api\RatingController',
    'App\Controller\Api\ReplyController',
    'App\Controller\Api\UserController',
    'App\Controller\Api\WishlistController',
    'App\Controller\Api\WorkController',
    'App\Controller\Web\Admin\AuthorsController',
    'App\Controller\Web\Admin\BookController',
    'App\Controller\Web\Admin\BooksController',
    'App\Controller\Web\Admin\CategoriesController',
    'App\Controller\Web\Admin\DashboardController',
    'App\Controller\Web\Admin\OrderController',
    'App\Controller\Web\Admin\OrdersController',
    'App\Controller\Web\Admin\SeriesController',
    'App\Controller\Web\Admin\UserController',
    'App\Controller\Web\Admin\UsersController',
    'App\Controller\Web\Admin\WorksController',
    'App\Controller\Web\AccountController',
    'App\Controller\Web\AuthorController',
    'App\Controller\Web\BookController',
    'App\Controller\Web\BooksController',
    'App\Controller\Web\CartController',
    'App\Controller\Web\CheckoutController',
    'App\Controller\Web\HomeController',
    'App\Controller\Web\OrderController',
    'App\Controller\Web\OrdersController',
    'App\Controller\Web\PaymentController',
    'App\Controller\Web\SecurityController',
    'App\Controller\Web\SeriesController',
    'App\Controller\Web\WishlistController',
    'App\Controller\Web\MapController',
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
