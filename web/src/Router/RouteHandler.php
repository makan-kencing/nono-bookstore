<?php

declare(strict_types=1);

namespace App\Router;

use App\Controller\Api\ApiController;
use App\Core\View;
use App\Exception\WebException;
use App\Exception\Wrapper\ApiExceptionWrapper;
use App\Exception\Wrapper\WebExceptionWrapper;
use PDO;

/**
 * @template T
 */
readonly class RouteHandler
{
    /**
     * @param class-string<T> $controller
     * @param string $method
     * @param ?RequireAuth $authConstraint
     */
    public function __construct(
        public string $controller,
        public string $method,
        public ?RequireAuth $authConstraint
    ) {
    }

    /**
     * @param PDO $pdo
     * @param View $view
     * @param array<string, string> $params
     * @return void
     * @throws WebExceptionWrapper
     * @throws ApiExceptionWrapper
     */
    public function handle(PDO $pdo, View $view, array $params): void
    {

        $controller = new $this->controller($pdo, $view);
        try {
            $controller->{$this->method}(...$params);
        } catch (WebException $e) {
            if (is_subclass_of($this->controller, ApiController::class))
                throw new ApiExceptionWrapper($e);
            throw new WebExceptionWrapper($e);
        }
    }
}
