<?php

declare(strict_types=1);

namespace App\Router;

use App\Controller\Controller;
use App\Exception\NotFoundException;
use App\Router\Method\Method;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;

class Router
{
    /** @var Route[] */
    private array $routes = [];

    public function register(Route $route): void
    {
        $this->routes[] = $route;
    }

    /**
     * @param class-string<Controller> $controller
     * @return void
     * @throws ReflectionException
     */
    public function registerController(string $controller): void
    {
        $reflectionClass = new ReflectionClass($controller);

        $pathPrefix = $reflectionClass->getAttributes(Path::class)[0] ?? '';
        if ($pathPrefix instanceof ReflectionAttribute) {
            $pathPrefix = $pathPrefix->newInstance()->path;
        }

        foreach ($reflectionClass->getMethods() as $reflectionMethod) {
            $methods = $reflectionMethod->getAttributes(Method::class, ReflectionAttribute::IS_INSTANCEOF);
            if (!$methods) {
                continue;
            }

            $path = $reflectionMethod->getAttributes(Path::class)[0] ?? '';
            if ($path instanceof ReflectionAttribute) {
                $path = $path->newInstance()->path;
            }
            $isRestful = (bool)$reflectionMethod->getAttributes(RESTful::class);

            $route = new Route(
                array_map(fn ($method) => $method->newInstance(), $methods),
                $pathPrefix . $path,
                $reflectionClass->getName(),
                $reflectionMethod->getName(),
                $isRestful
            );

            $this->register($route);
        }
    }

    /**
     * @throws NotFoundException
     */
    public function dispatch(string $method, string $uri): Route
    {
        // TODO: throw MethodNotAllowed
        foreach ($this->routes as $route) {
            if ($route->matchesUri($uri)) {
                if ($route->matchesMethod($method)) {
                    return $route;
                }
            }
        }
        throw new NotFoundException();
    }
}
