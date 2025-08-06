<?php

declare(strict_types=1);

namespace App\Router;

use App\Controller\Controller;
use App\Router\Method\HttpMethod;
use App\Router\Method\Method;
use JetBrains\PhpStorm\Language;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

/**
 * @phpstan-type Handler array{0: class-string<Controller>, 1: callable}
 * @phpstan-type HttpMethodMapping array<string, Handler>
 * @phpstan-type Routes array<string, HttpMethodMapping>
 */
class RouteMapping
{
    /** @var Routes */
    private array $routes = [];

    private static function matchesUri(#[Language('RegExp')] string $regex, string $uri): bool
    {
        return (bool)preg_match($regex, $uri);
    }

    private static function compilePath(string $path): string
    {
        return '{^' . preg_replace('/{([a-zA-Z_][a-zA-Z0-9_]*)}/', '(?<\1>[^/]+)', $path) . '$}';
    }

    /**
     * @param ReflectionClass<Controller>|ReflectionMethod $reflection
     * @return string[]
     */
    private static function getAnnotatedPaths(ReflectionClass|ReflectionMethod $reflection): array
    {
        $pathsReflection = $reflection->getAttributes(Path::class);

        return array_map(fn ($reflection) => $reflection->getArguments()[0], $pathsReflection);
    }

    /**
     * @param ReflectionMethod $reflection
     * @return HttpMethod[]
     */
    private static function getAnnotatedHttpMethods(ReflectionMethod $reflection): array
    {
        $httpMethodsReflection = $reflection->getAttributes(
            Method::class,
            ReflectionAttribute::IS_INSTANCEOF
        );

        return array_map(fn ($reflection) => $reflection->newInstance()::METHOD, $httpMethodsReflection);
    }

    /**
     * @param class-string<Controller> $controller
     * @return void
     * @throws ReflectionException | RouteInUseException
     */
    public function register(string $controller): void
    {
        $reflectionClass = new ReflectionClass($controller);

        $pathPrefix = self::getAnnotatedPaths($reflectionClass)[0] ?? '';

        foreach ($reflectionClass->getMethods() as $reflectionMethod) {
            $httpMethods = self::getAnnotatedHttpMethods($reflectionMethod);
            if (!$httpMethods) {
                continue;
            }

            $paths = self::getAnnotatedPaths($reflectionMethod);
            foreach ($httpMethods as $httpMethod) {
                foreach ($paths as $path) {
                    $fullpath = $pathPrefix . $path;
                    $regex = self::compilePath($fullpath);

                    $this->routes[$regex] ??= [];
                    if (isset($this->routes[$regex][$httpMethod->value])) {
                        $inUse = $this->routes[$regex][$httpMethod->value];
                        throw new RouteInUseException(
                            $httpMethod,
                            $fullpath,
                            $inUse[0] . '::' . $inUse[1],
                            $reflectionClass->getName() . '::' . $reflectionMethod->getName()
                        );
                    }

                    $this->routes[$regex][$httpMethod->value] = [
                        $reflectionClass->getName(), $reflectionMethod->getName()
                    ];
                }
            }
        }
    }

    /**
     * @param string $uri
     * @return ?array{0: string, 1: HttpMethodMapping}
     */
    public function getMethodMapping(string $uri): ?array
    {
        foreach (array_keys($this->routes) as $regex) {
            if (self::matchesUri($regex, $uri)) {
                return [$regex, $this->routes[$regex]];
            }
        }
        return null;
    }
}
