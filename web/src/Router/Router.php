<?php

declare(strict_types=1);

namespace App\Router;

use App\Controller\Api\ApiController;
use App\Controller\Controller;
use App\Core\View;
use App\Exception\MethodNotAllowedException;
use App\Exception\NotFoundException;
use App\Exception\WebException;
use App\Exception\Wrapper\ApiExceptionWrapper;
use App\Exception\Wrapper\ExceptionWrapper;
use App\Exception\Wrapper\WebExceptionWrapper;
use App\Router\Method\HttpMethod;
use JetBrains\PhpStorm\Language;
use PDO;
use ReflectionException;
use RuntimeException;

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
     * @throws NotFoundException | MethodNotAllowedException | ExceptionWrapper
     */
    public function dispatch(string $method, string $uri): void
    {
        // validate method is correct
        $_ = HttpMethod::from($method);

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
        $controller = new $handler[0]($this->pdo, $this->view);
        try {
            $controller->{$handler[1]}(...$params);
            return;
        } catch (WebException $e) {
            if (is_subclass_of($controller, ApiController::class)) {
                throw new ApiExceptionWrapper($e);
            }
            throw new WebExceptionWrapper($e);
        }
    }
}
