<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\UserCreateDTO;
use App\DTO\UserLoginContextDTO;
use App\DTO\UserLoginDTO;
use App\DTO\UserRegisterDTO;
use App\DTO\UserUpdateDTO;
use App\Entity\User\User;
use App\Entity\User\UserRole;
use App\Exception\ConflictException;
use App\Exception\ForbiddenException;
use App\Exception\NotFoundException;
use App\Repository\Query\UserCriteria;
use App\Repository\Query\UserQuery;
use App\Repository\UserRepository;
use App\Router\AuthRule;
use PDO;

readonly class UserService extends Service
{
    private UserRepository $userRepository;

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        $this->userRepository = new UserRepository($this->pdo);
    }

    public function checkUsernameExists(string $username): bool
    {
        $qb = UserQuery::withMinimalDetails()
            ->where(UserCriteria::byUsername())
            ->bind(':username', $username);

        return $this->userRepository->count($qb) != 0;
    }

    public function checkEmailExists(string $email): bool
    {

        $qb = UserQuery::withMinimalDetails()
            ->where(UserCriteria::byEmail())
            ->bind(':email', $email);

        return $this->userRepository->count($qb) != 0;
    }

    /**
     * @param UserRegisterDTO $dto
     * @return void
     * @throws ConflictException
     */
    public function register(UserRegisterDTO $dto): void
    {
        // TODO: add conflict message
        if ($this->checkUsernameExists($dto->username))
            throw new ConflictException([]);

        if ($this->checkEmailExists($dto->email))
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
            $user->username,
            $user->role
        )->jsonSerialize();
        return true;
    }

    public function logout(): void
    {
        unset($_SESSION['user']);
    }

    /**
     * @throws ConflictException
     * @throws ForbiddenException
     */
    public function create(UserCreateDTO $dto): void
    {
        // TODO: add conflict message
        if ($this->checkUsernameExists($dto->username))
            throw new ConflictException([]);

        if ($this->checkEmailExists($dto->email))
            throw new ConflictException([]);

        /** @var UserLoginContextDTO $context */
        $context = $_SESSION['user'];
        if (!AuthRule::HIGHER->check($context->role, $dto->role))
            throw new ForbiddenException();

        $user = new User();
        $user->username = $dto->username;
        $user->email = $dto->email;
        $user->hashedPassword = password_hash($dto->password, PASSWORD_DEFAULT);
        $user->role = $dto->role;
        $user->isVerified = false;

        $this->userRepository->insert($user);
    }

    /**
     * @throws ForbiddenException
     * @throws NotFoundException
     */
    public function update(UserUpdateDTO $dto): void
    {
        /** @var UserLoginContextDTO $context */
        $context = $_SESSION['user'];

        $qb = UserQuery::withMinimalDetails();
        if ($dto->id != null)
            $qb->where(UserCriteria::byId())
                ->bind(':id', $dto->id);
        else if ($dto->username != null)
            $qb->where(UserCriteria::byUsername())
                ->bind(':username', $dto->username);
        else if ($dto->email != null)
            $qb->where(UserCriteria::byEmail())
                ->bind(':email', $dto->email);
        else if ($dto->role != null)
            if (!AuthRule::HIGHER->check($context->role, $dto->role))
                throw new ForbiddenException();

        $user = $this->userRepository->getOne($qb);
        if ($user == null)
            throw new NotFoundException();

        if (!AuthRule::HIGHER->check($context->role, $user->role))
            throw new ForbiddenException();

        $dto->update($user);

        $this->userRepository->update($user);
    }

    public function delete(int $id): void
    {

        $this->userRepository->deleteById($id);
    }
}
