<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Core\View;
use App\DTO\Request\UserLoginDTO;
use App\DTO\Request\UserRegisterDTO;
use App\Exception\BadRequestException;
use App\Exception\ConflictException;
use App\Exception\UnauthorizedException;
use App\Exception\UnprocessableEntityException;
use App\Router\Method\POST;
use App\Router\Path;
use App\Router\RequireAuth;
use App\Service\AuthService;
use PDO;

#[Path('/api')]
readonly class AuthController extends ApiController
{
    private AuthService $authService;

    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);
        $this->authService = new AuthService($pdo);
    }

    /**
     * @throws BadRequestException
     * @throws UnprocessableEntityException
     * @throws ConflictException
     */
    #[POST]
    #[Path('/register')]
    public function register(): void
    {
        $dto = UserRegisterDTO::jsonDeserialize(self::getJsonBody());
        $dto->validate();

        $this->authService->register($dto);

        http_response_code(201);
    }

    /**
     * @throws UnprocessableEntityException
     * @throws BadRequestException
     * @throws UnauthorizedException
     */
    #[POST]
    #[Path('/login')]
    public function login(): void
    {
        $dto = UserLoginDTO::jsonDeserialize(self::getJsonBody());
        $dto->validate();

        if (!$this->authService->login($dto))
            throw new UnauthorizedException();

        http_response_code(200);
    }

    #[POST]
    #[Path('/logout')]
    #[RequireAuth(redirect: false)]
    public function logout(): void
    {
        $this->authService->logout();

        http_response_code(204);
    }
}
