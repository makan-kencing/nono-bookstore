<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Core\View;
use App\DTO\Request\ResetPasswordDTO;
use App\Exception\BadRequestException;
use App\Exception\UnprocessableEntityException;
use App\Router\Method\POST;
use App\Router\Method\PUT;
use App\Router\Path;
use App\Service\UserService;
use PDO;
use Random\RandomException;

#[Path('/api/password-resets')]
readonly class PasswordResetController extends ApiController
{
    private UserService $userService;

    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);
        $this->userService = new UserService($pdo);
    }

    /**
     * @throws BadRequestException
     * @throws RandomException
     * @throws UnprocessableEntityException
     */
    #[POST]
    public function requestReset(): void
    {
        $data = self::getJsonBody();
        $email = $data['email'] ?? throw new BadRequestException();

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            throw new UnprocessableEntityException();

        $this->userService->requestResetPassword($email);

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'If email exists, reset link was sent.']);
    }

    /**
     * @param string $selector
     * @return void
     * @throws BadRequestException
     * @throws \App\Exception\NotFoundException
     * @throws \App\Exception\UnprocessableEntityException
     */
    #[PUT]
    #[Path('/{selector}')]
    public function resetPassword(string $selector): void
    {
        $dto = ResetPasswordDTO::jsonDeserialize(self::getJsonBody());
        $dto->validate();

        $this->userService->resetPassword($selector, $dto->token, $dto->newPassword);

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Password has been reset successfully']);
    }
}
