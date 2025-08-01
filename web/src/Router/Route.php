<?php

declare(strict_types=1);

namespace App\Router;

use App\Controller\Controller;
use App\Core\View;
use App\Exception\Wrapper\ApiExceptionWrapper;
use App\Exception\Wrapper\WebExceptionWrapper;
use App\Router\Method\Method;
use PDO;
use Throwable;

readonly class Route
{
    public string $regex;

    /**
     * @param Method[] $methods
     * @param string $path
     * @param class-string<Controller> $controller
     * @param callable-string $handler
     * @param bool $restful
     */
    public function __construct(
        public array $methods,
        public string $path,
        public string $controller,
        public string $handler,
        public bool $restful = false
    ) {
        $this->regex = self::compileRegex($path);
    }

    private static function compileRegex(string $path): string
    {
        $paramPattern = '/{([a-zA-Z_][a-zA-Z0-9_]*)}/';
        return '{^' . preg_replace($paramPattern, '(?<\1>[^/]+)', $path) . '$}';
    }

    /**
     * @return array<string, string>
     */
    public function extractPathParams(string $uri): array
    {
        if (preg_match($this->regex, $uri, $matches)) {
            return array_filter($matches, fn ($key) => is_string($key), ARRAY_FILTER_USE_KEY);
        }
        return [];
    }

    public function matchesUri(string $uri): bool
    {
        return (bool)preg_match($this->regex, $uri);
    }

    public function matchesMethod(string $method): bool
    {
        return array_any(
            $this->methods,
            fn ($allowedMethod) => $method == $allowedMethod::METHOD
        );
    }

    /**
     * @throws WebExceptionWrapper
     * @throws ApiExceptionWrapper
     */
    public function handle(string $uri, PDO $pdo, View $view): void
    {
        try {
            $params = $this->extractPathParams($uri);

            $controller = new $this->controller($pdo, $view);
            $controller->{$this->handler}(...$params);
        } catch (Throwable $e) {
            if ($this->restful) {
                throw new ApiExceptionWrapper($e);
            }
            throw new WebExceptionWrapper($e);
        }
    }
}
