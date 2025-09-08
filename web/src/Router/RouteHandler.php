<?php

declare(strict_types=1);

namespace App\Router;

use App\Controller\Api\ApiController;
use App\Core\View;
use App\DTO\UserLoginContextDTO;
use App\Entity\User\UserRole;
use App\Exception\BadRequestException;
use App\Exception\ForbiddenException;
use App\Exception\UnauthorizedException;
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
     * @throws UnauthorizedException
     * @throws ForbiddenException
     * @throws BadRequestException
     */
    public function handleAuthMiddleware(): bool
    {
        assert($this->authConstraint != null);

        session_start();


        $_SESSION['user'] = new UserLoginContextDTO(
            1,
            'admin',
            UserRole::ADMIN,
        );
        $context = $_SESSION['user'] ?? null;
        if ($context == null) {
            if ($this->authConstraint->redirect)
                header('Location: /login');
            else
                throw new UnauthorizedException();
            return false;
        }

        if (!$this->authConstraint->check($context->role))
            throw new ForbiddenException();
        return true;
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

        try {
            if (is_subclass_of($this->controller, ApiController::class))
                header('Content-Type: application/json');

            if ($this->authConstraint)
                if (!$this->handleAuthMiddleware())
                    return;

            $controller = new $this->controller($pdo, $view);
            $controller->{$this->method}(...$params);
        } catch (WebException $e) {
            if (is_subclass_of($this->controller, ApiController::class))
                throw new ApiExceptionWrapper($e);
            throw new WebExceptionWrapper($e);
        }
    }
}
