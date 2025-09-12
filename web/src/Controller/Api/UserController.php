<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Core\View;
use App\DTO\Request\UserPasswordUpdateDTO;
use App\DTO\Request\UserProfileUpdateDTO;
use App\DTO\Request\UserUpdateDTO;
use App\Exception\BadRequestException;
use App\Exception\NotFoundException;
use App\Exception\UnprocessableEntityException;
use App\Router\Method\GET;
use App\Router\Method\POST;
use App\Router\Method\PUT;
use App\Router\Path;
use App\Router\RequireAuth;
use App\Service\UserService;
use PDO;

#[Path('/api/user')]
#[RequireAuth(redirect: false)]
readonly class  UserController extends ApiController
{
    private UserService $userService;

    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);
        $this->userService = new UserService($this->pdo);
    }

    #[GET]
    #[Path('/username/{username}')]
    public function checkUsernameExists(string $username): void
    {
        header('Content-Type: application/json');
        echo json_encode(['exists' => $this->userService->checkUsernameExists($username)]);
    }

    #[GET]
    #[Path('/email/{email}')]
    public function checkEmailExists(string $email): void
    {
        header('Content-Type: application/json');
        echo json_encode(['exists' => $this->userService->checkEmailExists($email)]);
    }

    /**
     * @param String $id
     * @return void
     * @throws BadRequestException
     * @throws NotFoundException
     * @throws UnprocessableEntityException
     * @throws \App\Exception\ForbiddenException
     * @throws \App\Exception\UnauthorizedException
     */
    #[PUT]
    #[Path('/{id}')]
    public function updateUser(String $id): void
    {
        $dto = UserUpdateDTO::jsonDeserialize(self::getJsonBody());
        $dto->validate();

        $this->userService->update($dto, (int) $id);

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'User updated successfully']);
    }

    /**
     * @param String $id
     * @return void
     * @throws BadRequestException
     * @throws NotFoundException
     * @throws UnprocessableEntityException
     * @throws \App\Exception\ForbiddenException
     * @throws \App\Exception\UnauthorizedException
     */
    #[PUT]
    #[Path('/update-profile/{id}')]
    public function updateUserProfile(String $id): void
    {
        $dto = UserProfileUpdateDTO::jsonDeserialize(self::getJsonBody());
        $dto->validate();

        $this->userService->updateUserProfile($dto, (int) $id);

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'User profile updated successfully']);
    }

    #[PUT]
    #[Path('/update-password/{id}')]
    public function updatePassword(string $id): void
    {
        $dto = UserPasswordUpdateDTO::jsonDeserialize(self::getJsonBody());
        $dto->validate();

        $this->userService->updatePassword($dto, (int) $id);

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Password updated successfully']);
    }

}
