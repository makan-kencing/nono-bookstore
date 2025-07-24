<?php

declare(strict_types=1);

/**
 * @var list<array{0: string|string[], 1: string, 2: array{0: class-string, 1: callable-string}}
 */
const ROUTES = [
    ['GET', '/', ['App\Controller\HomeController', 'index']],

    ['GET', '/book/{isbn:\d{13}}[/{slug:[a-z0-9-]+}]', ['App\Controller\BookController', 'viewBook']],
    ['GET', '/search', ['App\Controller\BookController', 'searchBook']],
    ['GET', '/orders', ['App\Controller\OrderController', 'viewOrders']],
    ['GET', '/order/{id:\d+}', ['App\Controller\OrderController', 'viewOrderDetails']],
    ['GET', '/cart', ['App\Controller\CartController', 'viewCart']],
    ['GET', '/cart/checkout', ['App\Controller\CheckoutController', 'viewCheckout']],
    ['GET', '/cart/payment', ['App\Controller\PaymentController', 'viewPayment']],
    ['GET', '/wishlist', ['App\Controller\WishlistController', 'viewWishlist']],
    ['GET', '/author/{slug:[a-z0-9-]+}', ['App\Controller\AuthorController', 'viewAuthor']],
    ['GET', '/series/{slug:[a-z0-9-]+}', ['App\Controller\SeriesController', 'viewSeries']],

    ['GET', '/admin/users', ['App\Controller\AdminUserController', 'viewUserList']],

    ['GET', '/api/user/exists', ['App\Controller\AdminUserController', 'checkUsernameExists']],
    ['POST', '/api/cart/add', ['App\Controller\CartController', 'addItem']],
    ['POST', '/api/cart/subtract', ['App\Controller\CartController', 'subtractItem']],
    ['POST', '/api/cart/remove', ['App\Controller\CartController', 'removeItem']],
    ['POST', '/api/wishlist/add', ['App\Controller\WishlistController', 'addItem']],
    ['POST', '/api/wishlist/remove', ['App\Controller\WishlistController', 'removeItem']]
];
