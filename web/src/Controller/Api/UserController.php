<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Core\View;
use App\DTO\Request\SearchDTO;
use App\DTO\Request\UserPasswordUpdateDTO;
use App\DTO\Request\UserProfileUpdateDTO;
use App\DTO\Request\UserUpdateDTO;
use App\DTO\Response\ImageUploadedDTO;
use App\Entity\User\UserRole;
use App\Exception\BadRequestException;
use App\Exception\ConflictException;
use App\Exception\ContentTooLargeException;
use App\Exception\ForbiddenException;
use App\Exception\NotFoundException;
use App\Exception\UnauthorizedException;
use App\Exception\UnprocessableEntityException;
use App\Router\AuthRule;
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
     * @throws ForbiddenException
     * @throws UnauthorizedException
     */
    #[PUT]
    #[Path('/{id}')]
    public function updateUser(string $id): void
    {
        $dto = UserUpdateDTO::jsonDeserialize(self::getJsonBody());
        $dto->validate();

        $this->userService->update($dto, (int)$id);

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'User updated successfully']);
    }

    /**
     * @param String $id
     * @return void
     * @throws BadRequestException
     * @throws NotFoundException
     * @throws UnprocessableEntityException
     * @throws ForbiddenException
     * @throws UnauthorizedException
     */
    #[PUT]
    #[Path('/update-profile/{id}')]
    public function updateUserProfile(string $id): void
    {
        $dto = UserProfileUpdateDTO::jsonDeserialize(self::getJsonBody());
        $dto->validate();

        $this->userService->updateUserProfile($dto, (int)$id);

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'User profile updated successfully']);
    }

    /**
     * @throws UnauthorizedException
     * @throws NotFoundException
     * @throws ForbiddenException
     * @throws BadRequestException
     * @throws UnprocessableEntityException
     */
    #[PUT]
    #[Path('/update-password/{id}')]
    public function updatePassword(string $id): void
    {
        $dto = UserPasswordUpdateDTO::jsonDeserialize(self::getJsonBody());
        $dto->validate();

        $this->authService->changePassword((int) $id, $dto->oldPassword, $dto->newPassword);

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Password updated successfully']);
    }

    /**
     * @throws UnauthorizedException
     * @throws ConflictException
     * @throws BadRequestException
     * @throws UnprocessableEntityException
     * @throws ContentTooLargeException
     * @throws NotFoundException
     * @throws ForbiddenException
     */
    #[POST]
    #[Path('/{userId}/profile/image')]
    public function setProfileImage(string $userId): void
    {
        $file = $_FILES['profile_image'];

        if (!$this->userService->canModify((int) $userId))
            throw new ForbiddenException();

        $file = $this->userService->uploadProfileImage((int) $userId, $file);

        header('Content-Type: application/json');
        echo json_encode(ImageUploadedDTO::fromFile($file));
    }


    #[PUT]
    #[Path('/{id}/block/toggle')]
    public function toggleBlock(string $id): void
    {
        $this->userService->toggleBlock((int)$id);
    }

    /**
     * @throws BadRequestException
     * @throws UnprocessableEntityException
     */
    #[GET]
    #[Path('/search/')]
    #[Path('/search/{query}')]
    #[RequireAuth([UserRole::STAFF], rule: AuthRule::HIGHER_OR_EQUAL, redirect: false)]
    public function search(?string $query = null): void
    {
        if ($query !== null)
            $_GET['query'] = $query;

        $dto = SearchDTO::jsonDeserialize($_GET);
        $dto->validate();

        $page = $this->userService->search($dto);

        if ($_SERVER['HTTP_ACCEPT'] === 'text/html') {
            header('Content-Type: text/html');
            echo $this->render(
                'admin/_component/_users_table.php',
                ['page' => $page, 'search' => $dto]
            );
        }
    }
}
