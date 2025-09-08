<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\Request\UserLoginDTO;
use App\DTO\Request\UserRegisterDTO;
use App\DTO\UserLoginContextDTO;
use App\Entity\User\User;
use App\Entity\User\UserRole;
use App\Exception\ConflictException;
use App\Repository\Query\UserCriteria;
use App\Repository\Query\UserQuery;
use App\Repository\UserRepository;
use PDO;
use UnexpectedValueException;

readonly class AuthService extends Service
{
    private UserRepository $userRepository;
    private UserService $userService;

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        $this->userRepository = new UserRepository($pdo);
        $this->userService = new UserService($pdo);
    }

    public static function getLoginContext(): ?UserLoginContextDTO
    {
        return $_SESSION['user'] ?? null;
    }

    /**
     * @param UserRegisterDTO $dto
     * @return void
     * @throws ConflictException
     */
    public function register(UserRegisterDTO $dto): void
    {
        // TODO: add conflict message
        if ($this->userService->checkUsernameExists($dto->username))
            throw new ConflictException([]);

        if ($this->userService->checkEmailExists($dto->email))
            throw new ConflictException([]);

        $user = new User();
        $user->username = $dto->username;
        $user->email = $dto->email;
        $user->hashedPassword = password_hash($dto->password, PASSWORD_DEFAULT);
        $user->role = UserRole::USER;
        $user->isVerified = false;

        $this->userRepository->insert($user);
    }

    public function login(UserLoginDTO $dto): bool
    {
        $qb = UserQuery::withMinimalDetails()
            ->where(UserCriteria::byEmail())
            ->bind(':email', $dto->email);
        $user = $this->userRepository->getOne($qb);

        // prevent timing attack even if there's no has to compare with
        if (!$user) {
            password_verify($dto->password, 'placeholder');
            return false;
        }

        if (!password_verify($dto->password, $user->hashedPassword))
            return false;

        if (session_status() != PHP_SESSION_ACTIVE)
            session_start();

        // TODO: check otp / prompt for otp verification

        // TODO: implement remember me tokens

        // TODO: log the login success / failure event

        $_SESSION['user'] = new UserLoginContextDTO(
            $user->id ?? throw new UnexpectedValueException('Logged in user does not have id'),
            $user->username,
            $user->role
        );
        return true;
    }

    public function logout(): void
    {
        unset($_SESSION['user']);
    }
}
