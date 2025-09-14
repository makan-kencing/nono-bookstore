<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\Request\AddressCreateDTO;
use App\DTO\Request\SearchDTO;
use App\DTO\Request\UserAddressDTO;
use App\DTO\Request\UserCreateDTO;
use App\DTO\Request\UserProfileUpdateDTO;
use App\DTO\Request\UserUpdateDTO;
use App\DTO\Response\PageResultDTO;
use App\DTO\UserLoginContextDTO;
use App\Entity\File;
use App\Entity\User\User;
use App\Entity\User\UserProfile;
use App\Entity\User\UserToken;
use App\Entity\User\UserTokenType;
use App\Exception\BadRequestException;
use App\Exception\ConflictException;
use App\Exception\ContentTooLargeException;
use App\Exception\ForbiddenException;
use App\Exception\NotFoundException;
use App\Exception\UnauthorizedException;
use App\Exception\UnprocessableEntityException;
use App\Repository\Query\AddressCriteria;
use App\Repository\Query\AddressQuery;
use App\Repository\Query\UserCriteria;
use App\Repository\Query\UserQuery;
use App\Repository\Query\UserTokenCriteria;
use App\Repository\Query\UserTokenQuery;
use App\Repository\UserAddressRepository;
use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use App\Repository\UserTokenRepository;
use App\Router\AuthRule;
use PDO;
use PDOException;

/**
 * @phpstan-import-type PhpFile from FileService
 */
readonly class UserService extends Service
{
    private UserRepository $userRepository;
    private UserProfileRepository $userProfileRepository;
    private UserAddressRepository $userAddressRepository;
    private FileService $fileService;

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        $this->userRepository = new UserRepository($this->pdo);
        $this->userProfileRepository = new UserProfileRepository($pdo);
        $this->userAddressRepository = new UserAddressRepository($pdo);
        $this->fileService = new FileService($this->pdo);
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

    public function canModify(User|UserLoginContextDTO $editor, User $target): bool
    {
        return $editor->id === $target->id || AuthRule::HIGHER->check($editor->role, $target->role);
    }

    public function getPlainUser(int $id): ?User
    {
        $qb = UserQuery::withMinimalDetails();
        $qb->where(UserCriteria::byId(alias: 'u'))
            ->bind(':id', $id);
        return $this->userRepository->getOne($qb);
    }

    public function getUserForProfile(int $id): ?User
    {
        $qb = UserQuery::asProfile();
        $qb->where(UserCriteria::byId(alias: 'u'))
            ->bind(':id', $id);
        return $this->userRepository->getOne($qb);
    }

    public function getUserForAddressesBook(int $id): ?User
    {
        $qb = UserQuery::withAddress();
        $qb->where(UserCriteria::byId(alias: 'u'))
            ->bind(':id', $id);
        return $this->userRepository->getOne($qb);
    }

    /**
     * @throws ConflictException
     * @throws ForbiddenException
     * @throws UnauthorizedException
     */
    public function create(UserCreateDTO $dto): void
    {
        $context = $this->getSessionContext();
        if ($context === null)
            throw new UnauthorizedException();

        // TODO: add conflict message
        if ($this->checkUsernameExists($dto->username))
            throw new ConflictException([]);

        if ($this->checkEmailExists($dto->email))
            throw new ConflictException([]);

        if (!AuthRule::HIGHER->check($context->role, $dto->role))
            throw new ForbiddenException();

        $user = $dto->toUser();
        $this->userRepository->insert($user);
    }

    /**
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function update(UserUpdateDTO $dto, int $userId): void
    {
        $context = $this->getSessionContext();
        if ($context === null)
            throw new UnauthorizedException();

        $user = $this->getPlainUser($userId);
        if ($user === null)
            throw new NotFoundException();

        if (!$this->canModify($context, $user))
            throw new ForbiddenException();

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

        $user = $this->getPlainUser($id);
        if ($user == null)
            throw new NotFoundException();

        if (!$this->canModify($context, $user))
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
        if ($context === null)
            throw new UnauthorizedException();

        $user = $this->getUserForProfile($id);
        if ($user === null)
            throw new NotFoundException;

        if (!$this->canModify($context, $user))
            throw new ForbiddenException();

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

    /**
     * @throws UnauthorizedException
     * @throws NotFoundException
     * @throws ForbiddenException
     */
    public function updateUserAddress(UserAddressDTO $dto, int $addressId): void
    {
        $context = $this->getSessionContext();
        if ($context === null)
            throw new UnauthorizedException();

        $qb = AddressQuery::minimal();
        $qb->where(AddressCriteria::byId(alias: 'a'))
            ->bind(':id', $addressId);

        $address = $this->userRepository->getOne($qb);
        if ($address === null)
            throw new NotFoundException();

        if (!$this->canModify($context, $address->user))
            throw new ForbiddenException();

        $dto->update($address);
        $this->userAddressRepository->update($address);
    }

    /**
     * @throws UnauthorizedException
     * @throws NotFoundException
     * @throws ForbiddenException
     */
    public function deleteAddress(int $addressId): void
    {
        $context = $this->getSessionContext();
        if ($context === null)
            throw new UnauthorizedException();

        $qb = AddressQuery::minimal();
        $qb->where(AddressCriteria::byId(alias: 'a'))
            ->bind(':id', $addressId);

        $address = $this->userRepository->getOne($qb);
        if ($address === null)
            throw new NotFoundException();

        if (!$this->canModify($context, $address->user))
            throw new ForbiddenException();

        $this->userAddressRepository->deleteAddress($address->id);
    }

    /**
     * @throws UnauthorizedException
     * @throws NotFoundException
     * @throws ForbiddenException
     */
    public function setAddressDefault(int $addressId): void
    {
        $context = $this->getSessionContext();
        if ($context === null)
            throw new UnauthorizedException();

        $qb = AddressQuery::minimal();
        $qb->where(AddressCriteria::byId(alias: 'a'))
            ->bind(':id', $addressId);

        $address = $this->userRepository->getOne($qb);
        if ($address === null)
            throw new NotFoundException();

        if (!$this->canModify($context, $address->user))
            throw new ForbiddenException();

        $this->userAddressRepository->setDefault($address);
    }

    /**
     * @param PhpFile
     * @throws UnauthorizedException
     * @throws ConflictException
     * @throws BadRequestException
     * @throws UnprocessableEntityException
     * @throws ContentTooLargeException
     */
    public function uploadProfileImage(array $file): File
    {
        $file = $this->fileService->uploadImage($file);
        $this->userRepository->setProfileImage($file->user->id, $file->id);

        return $file;
    }

    public function toggleBlock(int $userId): void
    {
        $this->userRepository->toggleBlock($userId);
    }

    /**
     * @param SearchDTO $dto
     * @return PageResultDTO<User>
     */
    public function search(SearchDTO $dto): PageResultDTO
    {
        $qb = UserQuery::userListings();

        $predicates = UserCriteria::byUsernameLike(alias: 'u');
        $qb->bind(':username', '%' . ($dto->query ?? '') . '%');

        $qb->where($predicates);
        $qb->page($dto->toPageRequest());

        $this->pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
        try {
            return new PageResultDTO(
                $this->userRepository->get($qb),
                $this->userRepository->count($qb),
                $dto->toPageRequest()
            );
        } finally {
            $this->pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
        }
    }
}
