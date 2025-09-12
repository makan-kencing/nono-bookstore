<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\Request\AddressCreateDTO;
use App\DTO\Request\UserAddressDTO;
use App\DTO\Request\UserCreateDTO;
use App\DTO\Request\UserPasswordUpdateDTO;
use App\DTO\Request\UserProfileUpdateDTO;
use App\DTO\Request\UserUpdateDTO;
use App\DTO\UserLoginContextDTO;
use App\Entity\User\Address;
use App\Entity\User\User;
use App\Entity\User\UserProfile;
use App\Exception\ConflictException;
use App\Exception\ForbiddenException;
use App\Exception\NotFoundException;
use App\Exception\UnauthorizedException;
use App\Exception\UnprocessableEntityException;
use App\Repository\FileRepository;
use App\Repository\Query\AddressCriteria;
use App\Repository\Query\AddressQuery;
use App\Repository\Query\UserCriteria;
use App\Repository\Query\UserQuery;
use App\Repository\UserAddressRepository;
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

    private UserAddressRepository $userAddressRepository;
    private FileRepository $fileRepository;

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        $this->userRepository = new UserRepository($this->pdo);
        $this->userProfileRepository = new UserProfileRepository($pdo);
        $this->userAddressRepository = new UserAddressRepository($pdo);
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

        if ($user->id !== (int)$context->id) {
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
     * @param int $id
     * @return void
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function updateUserProfile(UserProfileUpdateDTO $dto, int $id): void
    {
        $context = $this->getSessionContext();
        if ($context === null) {
            throw new UnauthorizedException();
        }

        // ensure user exists
        $qb = UserQuery::withMinimalDetails()
            ->leftJoin('profile', 'up')
            ->where(UserCriteria::byId())
            ->bind(':id', $id);

        $user = $this->userRepository->getOne($qb);
        if ($user === null) {
            throw new NotFoundException;
        }

        if ($user->id !== (int)$context->id) {
            if (!AuthRule::HIGHER->check($context->role, $user->role)) {
                throw new ForbiddenException();
            }
        }

        // Ensure profile exists before updating
        if ($user->profile === null) {
            $user->profile = new UserProfile();
            $user->profile->user = $user;
            $dto->updateProfile($user->profile);
            $this->userProfileRepository->insert($user->profile);
        } else {
            $dto->updateProfile($user->profile);
            $this->userProfileRepository->update($user->profile);
        }
    }

    /**
     * @throws UnauthorizedException
     * @throws NotFoundException
     * @throws ForbiddenException
     */
    public function updateUserAddress(UserAddressDTO $dto, int $id): void
    {
        $context = $this->getSessionContext();
        if ($context === null)
            throw new UnauthorizedException();

        $qb = AddressQuery::minimal();
        $qb->join('user', 'u')
            ->where(AddressCriteria::byId(alias: 'a'))
            ->bind(':id', $id);

        $address = $this->userRepository->getOne($qb);
        if ($address === null)
            throw new NotFoundException;

        if ($address->user->id !== $context->id)
            if (!AuthRule::HIGHER->check($context->role, $address->user->role))
                throw new ForbiddenException();

        $dto->update($address);
        $this->userAddressRepository->update($address);
    }

    /**
     * @throws UnauthorizedException
     */
    public function createAddress(AddressCreateDTO $dto): void
    {
        $context = $this->getSessionContext();
        if ($context === null)
            throw new UnauthorizedException();

        $address = $dto->toAddress();
        $address->user = $context->toUserReference();

        $this->userAddressRepository->insert($address);

        if ($dto->default)
            $this->userAddressRepository->setDefault($address);
    }

    public function deleteAddress(int $id): void{
        $context = $this->getSessionContext();
        if ($context === null)
            throw new UnauthorizedException();

        if ($id != $context->toUserReference()){
            throw new ForbiddenException([]);
        }

        $this->userAddressRepository->deleteAddress($id);

    }

    public function setAddressDefault(int $addressId): void
    {
        $context = $this->getSessionContext();
        if ($context === null)
            throw new UnauthorizedException();

        $address = new Address();
        $address->id = $addressId;
        $address->user = $context->toUserReference();

        $this->userAddressRepository->setDefault($address);
    }
    /**
     * @throws UnauthorizedException
     * @throws NotFoundException
     * @throws ForbiddenException
     */
    public function updatePassword(UserPasswordUpdateDTO $dto, int $userId): void
    {
        $context = $this->getSessionContext();
        if ($context === null) {
            throw new UnauthorizedException();
        }

        $qb = UserQuery::withMinimalDetails()
            ->where(UserCriteria::byId())
            ->bind(':id', $userId);

        $user = $this->userRepository->getOne($qb);
        if ($user === null) {
            throw new NotFoundException();
        }

        // Only self or higher role can change
        if ($user->id !== (int)$context->id) {
            if (!AuthRule::HIGHER->check($context->role, $user->role)) {
                throw new ForbiddenException();
            }
        }

        // Check old password
        if (!password_verify($dto->oldPassword, $user->hashedPassword)) {
            throw new UnprocessableEntityException([[
                "field" => "old_password",
                "type" => "invalid",
                "reason" => "Old password is incorrect"
            ]]);
        }

        // Update with new password
        $user->hashedPassword = password_hash($dto->newPassword, PASSWORD_DEFAULT);
        $this->userRepository->update($user);
    }

}
