<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\Request\UserCreateDTO;
use App\DTO\Request\UserProfileUpdateDTO;
use App\DTO\Request\UserUpdateDTO;
use App\DTO\UserLoginContextDTO;
use App\Entity\User\User;
use App\Entity\User\UserProfile;
use App\Exception\ConflictException;
use App\Exception\ForbiddenException;
use App\Exception\NotFoundException;
use App\Exception\UnauthorizedException;
use App\Repository\FileRepository;
use App\Repository\Query\UserCriteria;
use App\Repository\Query\UserProfileCriteria;
use App\Repository\Query\UserQuery;
use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use App\Router\AuthRule;
use DateTime;
use PDO;
use PDOException;

readonly class UserService extends Service
{
    private UserRepository $userRepository;
    private UserProfileRepository $userProfileRepository;
    private FileRepository $fileRepository;

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        $this->userRepository = new UserRepository($this->pdo);
        $this->userProfileRepository = new UserProfileRepository($pdo);
        $this->fileRepository = new FileRepository($pdo);
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
        $context = $this->getSessionContext();
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

//    /**
//     * @throws ForbiddenException
//     * @throws NotFoundException
//     * @throws UnauthorizedException
//     */
//    public function update(UserUpdateDTO $dto, string $id): void
//    {
//        $context = $this->getSessionContext();
//        if ($context === null)
//            throw new UnauthorizedException();
//
//        $qb = UserQuery::withMinimalDetails();
//        if ($dto->id != null)
//            $qb->where(UserCriteria::byId())
//                ->bind(':id', $dto->id);
//        else if ($dto->username != null)
//            $qb->where(UserCriteria::byUsername())
//                ->bind(':username', $dto->username);
//        else if ($dto->email != null)
//            $qb->where(UserCriteria::byEmail())
//                ->bind(':email', $dto->email);
//        else if ($dto->role != null)
//            if (!AuthRule::HIGHER->check($context->role, $dto->role))
//                throw new ForbiddenException();
//
//        $user = $this->userRepository->getOne($qb);
//        if ($user == null)
//            throw new NotFoundException();
//
//        if ($user->id !== $context->id)
//            if (!AuthRule::HIGHER->check($context->role, $user->role))
//                throw new ForbiddenException();
//
//        $dto->update($user);
//
//        $this->userRepository->update($user);
//    }

    /**
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function update(UserUpdateDTO $dto, int $userId): void
    {
        $context = $this->getSessionContext();
        if ($context === null) {
            throw new UnauthorizedException();
        }

        $qb = UserQuery::withMinimalDetails();
            $qb->where(UserCriteria::byId())
                ->bind(':id', $userId);

        $user = $this->userRepository->getOne($qb);
        if ($user === null) {
            throw new NotFoundException();
        }

        if ($user->id !== (int) $context->id) {
            if (!AuthRule::HIGHER->check($context->role, $user->role)) {
                throw new ForbiddenException([
                    "userId" => $user->id,
                    "contextId" => $context->id,
                    "contextRole" => $context->role,
                    "userRole" => $user->role,
                ]);
            }
        }

        $dto->update($user);
        $this->userRepository->update($user);
    }

    /**
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws UnauthorizedException
     * @throws ConflictException
     */
    public function delete(int $id): void
    {
        $context = $this->getSessionContext();
        if ($context === null)
            throw new UnauthorizedException();

        $qb = UserQuery::withMinimalDetails();
        $qb->where(UserCriteria::byId())
            ->bind(':id', $id);

        $user = $this->userRepository->getOne($qb);
        if ($user == null)
            throw new NotFoundException();

        if (!AuthRule::HIGHER->check($context->role, $user->role))
            throw new ForbiddenException();

        try {
            $this->userRepository->deleteById($id);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000)  // integrity constraint violation
                throw new ConflictException([]);
            throw $e;
        }
    }

    /**
     * @param UserProfileUpdateDTO $dto
     * @return void
     * @throws UnauthorizedException
     */
    public function updateUserProfile(UserProfileUpdateDTO $dto): void
    {
        $context = $this->getSessionContext();
        if ($context === null) {
            throw new UnauthorizedException();
        }

        $userProfile = new UserProfile();
        $userProfile->id = $context->id;
        $dto->updateProfile($userProfile);
        $this->userProfileRepository->updateProfile($userProfile);
    }
}
