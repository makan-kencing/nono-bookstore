<?php

declare(strict_types=1);

/**
 * @var list<array{0: string|string[], 1: string, 2: array{0: class-string, 1: callable-string}}
 */
const ROUTES = [
    ['GET', '/', ['App\Controller\HomeController', 'index']]
];
