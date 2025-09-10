<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\Request\UserCreateDTO;
use App\DTO\Request\UserUpdateDTO;
use App\DTO\UserLoginContextDTO;
use App\Entity\User\User;
use App\Exception\ConflictException;
use App\Exception\ForbiddenException;
use App\Exception\NotFoundException;
use App\Repository\Query\UserCriteria;
use App\Repository\Query\UserQuery;
use App\Repository\UserRepository;
use App\Router\AuthRule;
use PDO;
use RuntimeException;

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

    private function getUserIdFromSession(): int
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            throw new RuntimeException("User is not logged in.");
        }
        return (int)$_SESSION['user_id'];
    }

    public function updateProfile(array $postData): void
    {
        $userId = $this->getUserIdFromSession();

        if (isset($postData['username'])) {
            $this->userRepository->updateUsername($userId, $postData['username']);
        }

        if (isset($postData['email'])) {
            $this->userRepository->updateEmail($userId, $postData['email']);
        }

        if (isset($postData['contact_no'])) {
            $this->userRepository->updateContact($userId, $postData['contact_no']);
        }

        if (isset($postData['dob'])) {
            $this->userRepository->updateDob($userId, $postData['dob']);
        }

        if (
            isset($postData['current_password'], $postData['new_password'], $postData['confirm_password'])
            && $postData['current_password'] !== ''
        ) {
            $this->changePassword(
                $userId,
                $postData['current_password'],
                $postData['new_password'],
                $postData['confirm_password']
            );
        }
    }

    public function changePassword(int $userId, string $currentPassword, string $newPassword, string $confirmPassword): void
    {
        $stmt = $this->pdo->prepare("SELECT hashed_password FROM user WHERE id = :id");
        $stmt->execute(['id' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row || !password_verify($currentPassword, $row['hashed_password'])) {
            throw new RuntimeException("Current password is incorrect.");
        }

        if ($newPassword !== $confirmPassword) {
            throw new RuntimeException("New passwords do not match.");
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $this->userRepository->updatePassword($userId, $hashedPassword);
    }
}
