<?php

declare(strict_types=1);

/**
 * @var list<array{0: string|string[], 1: string, 2: array{0: class-string, 1: callable-string}}>
 */
const ROUTES = [
    // public webpages
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

    // admin webpages
    ['GET', '/admin/users', ['App\Controller\AdminUserController', 'viewUserList']],

    // public api endpoints
    ['GET', '/api/user/exists', ['App\Controller\AdminUserController', 'checkUsernameExists']],
    ['POST', '/api/cart/item', ['App\Controller\CartController', 'addItem']],
    ['PUT', '/api/cart/item', ['App\Controller\CartController', 'subtractItem']],
    ['DELETE', '/api/cart/item', ['App\Controller\CartController', 'removeItem']],
    ['POST', '/api/wishlist/item', ['App\Controller\WishlistController', 'addItem']],
    ['DELETE', '/api/wishlist/item', ['App\Controller\WishlistController', 'removeItem']],
    ['POST', '/api/rating', ['App\Controller\RatingController', 'submitRating']],
    ['PUT', '/api/rating', ['App\Controller\RatingController', 'editRating']],
    ['DELETE', '/api/rating', ['App\Controller\RatingController', 'deleteRating']],
    ['POST', '/api/reply', ['App\Controller\ReplyController', 'submitReply']],
    ['PUT', '/api/reply', ['App\Controller\ReplyController', 'editReply']],
    ['DELETE', '/api/reply', ['App\Controller\ReplyController', 'deleteReply']],

    // admin api endpoints
    ['POST', '/api/user', ['App\Controller\AdminUserController', 'addUser']]
];
