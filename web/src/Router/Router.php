<?php

declare(strict_types=1);

namespace App\Router;

use App\Controller\Controller;
use App\Core\View;
use App\Exception\BadRequestException;
use App\Exception\MethodNotAllowedException;
use App\Exception\NotFoundException;
use App\Exception\Wrapper\ExceptionWrapper;
use App\Router\Method\HttpMethod;
use JetBrains\PhpStorm\Language;
use PDO;
use ReflectionException;
use RuntimeException;
use ValueError;

class Router
{
    private RouteMapping $routes;

    public function __construct(
        public readonly PDO $pdo,
        public readonly View $view
    ) {
        $this->routes = new RouteMapping();
    }

    /**
     * @return array<string, string>
     */
    private static function extractPathParams(#[Language('RegExp')] string $regex, string $uri): array
    {
        if (preg_match($regex, $uri, $matches)) {
            return array_filter($matches, fn ($key) => is_string($key), ARRAY_FILTER_USE_KEY);
        }
        return [];
    }

    /**
     * @param class-string<Controller> $controller
     * @return void
     * @throws RouteInUseException
     */
    public function register(string $controller): void
    {
        try {
            $this->routes->register($controller);
        } catch (ReflectionException $e) {
            throw new RuntimeException();
        }
    }

    /**
     * @throws NotFoundException
     * @throws MethodNotAllowedException
     * @throws ExceptionWrapper
     * @throws BadRequestException
     */
    public function dispatch(string $method, string $uri): void
    {
        // validate method is correct
        try {
            $_ = HttpMethod::from($method);
        } catch (ValueError) {
            throw new BadRequestException();
        }

        $mapping = $this->routes->getMethodMapping($uri);
        if (!$mapping) {
            throw new NotFoundException();
        }

        $regex = $mapping[0];
        $httpMethodMap = $mapping[1];
        if (!array_key_exists($method, $httpMethodMap)) {
            throw new MethodNotAllowedException(array_keys($httpMethodMap));
        }

        $handler = $httpMethodMap[$method];
        $params = self::extractPathParams($regex, $uri);
        $handler->handle($this->pdo, $this->view, $params);
    }
}
