<?php

declare(strict_types=1);

namespace App\Router;

use App\Controller\Controller;
use App\Core\View;
use App\Exception\MethodNotAllowedException;
use App\Exception\NotFoundException;
use App\Exception\WebException;
use App\Exception\Wrapper\ApiExceptionWrapper;
use App\Exception\Wrapper\WebExceptionWrapper;
use App\Router\Method\HttpMethod;
use App\Router\Method\Method;
use JetBrains\PhpStorm\Language;
use PDO;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

/**
 * @phpstan-type Handler callable<Controller>
 * @phpstan-type HttpMethodMapping array<string, Handler>
 * @phpstan-type Routes array<string, HttpMethodMapping>
 */
class Router
{
    /** @var Routes */
    private array $routes = [];

    public function __construct(
        public readonly PDO $pdo,
        public readonly View $view
    ) {
    }

    private static function compile(string $path): string
    {
        return '{^' . preg_replace('/{([a-zA-Z_][a-zA-Z0-9_]*)}/', '(?<\1>[^/]+)', $path) . '$}';
    }

    /**
     * @return array<string, string>
     */
    public static function extractPathParams(#[Language('RegExp')] string $regex, string $uri): array
    {
        if (preg_match($regex, $uri, $matches)) {
            return array_filter($matches, fn ($key) => is_string($key), ARRAY_FILTER_USE_KEY);
        }
        return [];
    }

    public static function matchesUri(#[Language('RegExp')] string $regex, string $uri): bool
    {
        return (bool)preg_match($regex, $uri);
    }

    public static function getPath(ReflectionClass|ReflectionMethod $reflection): string
    {
        $pathReflection = $reflection->getAttributes(Path::class)[0] ?? null;

        if ($pathReflection) {
            $path = $pathReflection->getArguments()[0];
            assert(is_string($path));

            return $path;
        }

        return '';
    }

    /**
     * @param class-string<Controller> $controller
     * @return void
     * @throws ReflectionException | RouteInUseException
     */
    public function register(string $controller): void
    {
        $reflectionClass = new ReflectionClass($controller);

        $pathPrefix = self::getPath($reflectionClass);

        foreach ($reflectionClass->getMethods() as $reflectionMethod) {
            $methodsReflection = $reflectionMethod->getAttributes(Method::class, ReflectionAttribute::IS_INSTANCEOF);
            if (!$methodsReflection) {
                continue;
            }

            foreach ($methodsReflection as $methodReflection) {
                $path = self::getPath($reflectionMethod);
                $httpMethod = $methodReflection->newInstance()::METHOD;
                $regex = self::compile($pathPrefix . $path);

                $this->routes[$regex] ??= [];
                if (isset($this->routes[$regex][$httpMethod->value])) {
                    throw new RouteInUseException(
                        $httpMethod,
                        $path,
                        $this->routes[$regex][$httpMethod->value],
                        $methodReflection->getName()
                    );
                }

                $this->routes[$regex][$httpMethod->value] = [$reflectionClass->getName(), $reflectionMethod->getName()];
            }
        }
    }

    /**
     * @throws NotFoundException | MethodNotAllowedException
     */
    public function dispatch(string $method, string $uri): void
    {
        // validate method is correct
        $_ = HttpMethod::from($method);

        foreach (array_keys($this->routes) as $regex) {
            if (self::matchesUri($regex, $uri)) {
                $httpMethodMap = $this->routes[$regex];

                if (!array_key_exists($method, $httpMethodMap)) {
                    throw new MethodNotAllowedException(array_keys($httpMethodMap));
                }

                $params = self::extractPathParams($regex, $uri);

                $handler = $httpMethodMap[$method];
                $controller = new $handler[0]($this->pdo, $this->view);

                try {
                    $controller->{$handler[1]}(...$params);
                    return;
                } catch (WebException $e) {
                    if (str_starts_with($uri, '/api')) {
                        throw new ApiExceptionWrapper($e);
                    }
                    throw new WebExceptionWrapper($e);
                }
            }
        }

        throw new NotFoundException();
    }
}
