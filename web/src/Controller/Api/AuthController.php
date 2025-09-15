<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\DTO\Request\OTPRegisterDTO;
use App\DTO\Request\UserLoginDTO;
use App\DTO\Request\UserRegisterDTO;
use App\Exception\BadRequestException;
use App\Exception\ConflictException;
use App\Exception\ForbiddenException;
use App\Exception\NotFoundException;
use App\Exception\UnauthorizedException;
use App\Exception\UnprocessableEntityException;
use App\Router\Method\DELETE;
use App\Router\Method\POST;
use App\Router\Path;
use App\Router\RequireAuth;
use App\Service\LoginResult;

#[Path('/api')]
readonly class AuthController extends ApiController
{
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

        switch ($this->authService->login($dto)) {
            case LoginResult::INVALID:
                throw new UnauthorizedException();
            case LoginResult::TWO_FACTOR_REQUIRED:
                throw new UnauthorizedException(['2fa' => true]);
            case LoginResult::SUCCESS:
                break;
        }

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

    /**
     * @throws UnauthorizedException
     * @throws UnprocessableEntityException
     * @throws BadRequestException
     */
    #[POST]
    #[RequireAuth]
    #[Path('/register-2fa')]
    public function register2FA(): void
    {
        $dto = OTPRegisterDTO::jsonDeserialize($_POST);
        $dto->validate();

        if (!$this->authService->register2FA($dto))
            throw new UnauthorizedException();

        http_response_code(204);
    }

    /**
     * @throws UnauthorizedException
     * @throws NotFoundException
     * @throws ForbiddenException
     * @throws BadRequestException
     */
    #[DELETE]
    #[RequireAuth]
    #[Path('/2fa')]
    public function remove2FA(): void
    {
        $json = self::getJsonBody();

        $code = $json['code'] ?? throw new BadRequestException();
        $this->authService->unregister2FA($code);

        http_response_code(204);
    }
}
