<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Core\View;
use App\Entity\User\User;
use App\Entity\User\UserRole;
use App\Exception\BadRequestException;
use App\Exception\ConflictException;
use App\Exception\UnprocessableEntityException;
use App\Repository\Query\QueryUserCount;
use App\Repository\UserRepository;
use App\Router\Method\GET;
use App\Router\Method\POST;
use App\Router\Path;
use PDO;

#[Path('/api/user')]
readonly class UserController extends ApiController
{
    private UserRepository $userRepository;

    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);
        $this->userRepository = new UserRepository($this->pdo);
    }

    #[GET]
    #[Path('/username/{username}')]
    public function checkUsernameExists(string $username): void
    {
        $query = new QueryUserCount();
        $query->username = $username;

        $count = $this->userRepository->count($query);

        header('Content-type: application/json');
        echo json_encode(['exists' => $count != 0]);
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
        header('Content-type: application/json');

        $_POST = self::getJsonBody();

        if (!isset($_POST['username']) || !isset($_POST['email']) || !isset($_POST['password'])) {
            throw new BadRequestException();
        }

        // Validate email format
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            throw new UnprocessableEntityException([]);
        }

        // TODO: validate password format

        // Check if username already exists
        $query = new QueryUserCount();
        $query->username = $_POST['username'];
        if ($this->userRepository->count($query) > 0) {
            throw new ConflictException([]);
        }

        // Check if email already exists
        $query = new QueryUserCount();
        $query->email = $_POST['email'];
        if ($this->userRepository->count($query) > 0) {
            throw new ConflictException([]);
        }

        // Create new user
        $user = new User();
        $user->username = trim($_POST['username']);
        $user->email = trim($_POST['email']);
        $user->hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $user->role = UserRole::USER;
        $user->isVerified = false;

        $this->userRepository->insert($user);

        // Return success response
        http_response_code(201); // Created
        echo json_encode([
            "status" => "success",
            "message" => "User registered successfully",
            "data" => [
                "username" => $user->username,
                "email" => $user->email,
                "role" => $user->role->name,
                "isVerified" => $user->isVerified
            ]
        ]);
    }
}
