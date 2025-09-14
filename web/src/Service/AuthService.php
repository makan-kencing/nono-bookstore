<?php

declare(strict_types=1);

namespace App\Service;

use App\Core\Clock;
use App\DTO\Request\OTPRegisterDTO;
use App\DTO\Request\UserLoginDTO;
use App\DTO\Request\UserRegisterDTO;
use App\DTO\Response\OTPGenerateDTO;
use App\DTO\UserLoginContextDTO;
use App\Entity\User\User;
use App\Entity\User\UserRole;
use App\Entity\User\UserTokenType;
use App\Exception\ConflictException;
use App\Exception\ForbiddenException;
use App\Exception\NotFoundException;
use App\Exception\UnauthorizedException;
use App\Repository\Query\UserCriteria;
use App\Repository\Query\UserQuery;
use App\Repository\UserRepository;
use OTPHP\TOTP;
use PDO;
use Psr\Clock\ClockInterface;
use Random\RandomException;

readonly class AuthService extends Service
{
    private UserRepository $userRepository;
    private UserService $userService;
    private ClockInterface $clock;

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        $this->userRepository = new UserRepository($pdo);
        $this->userService = new UserService($pdo);
        $this->clock = new Clock();
    }

    public static function getLoginContext(): ?UserLoginContextDTO
    {
        return $_SESSION['user'] ?? null;
    }

    public function invalidateSession(): void
    {
        unset($_SESSION['user']);
        // Remove remember me cookie
        setcookie('remember_me', '', time() - 3600, '/', '', true, true);
    }

    /**
     * @throws RandomException
     */
    public function setSessionAs(User $user, bool $rememberMe = false): void
    {
        $_SESSION['user'] = UserLoginContextDTO::fromUser($user);

        if ($rememberMe) {
            $tokenData = $this->userService->createRememberMeToken($user);
            $cookieValue = $tokenData['selector'] . ':' . $tokenData['token'];
            setcookie('remember_me', $cookieValue, [
                'expires' => time() + 30 * 24 * 60 * 60,
                'path' => '/',
                'httponly' => true,
                'secure' => true,
                'samesite' => 'Strict'
            ]);
        }
    }

    public function generateTotp(): OTPGenerateDTO
    {
        $otp = TOTP::generate($this->clock);
        $otp->setLabel('Novelty N Nonsense');

        return new OTPGenerateDTO(
            $otp->getSecret(),
            $otp->getQrCodeUri(
                'https://api.qrserver.com/v1/create-qr-code/?data=[DATA]&size=300x300&ecc=M',
                '[DATA]'
            )
        );
    }

    /**
     * @param non-empty-string $secret
     * @param non-empty-string $code
     * @return bool
     */
    public function verifyTotp(string $secret, string $code): bool
    {
        $otp = TOTP::createFromSecret($secret, $this->clock);
        return $otp->verify($code);
    }

    /**
     * @throws RandomException
     */
    public function loginWithRememberMe(): bool
    {
        [$selector, $token] = explode(':', $_COOKIE['remember_me']);
        $userToken = $this->userService->getTokenBySelector($selector, UserTokenType::REMEMBER_ME);

        if ($userToken === null)
            return false;

        if (!password_verify($token, $userToken->token))
            return false;

        assert($userToken->user->id !== null);
        $user = $this->userService->getPlainUser($userToken->user->id);
        if ($user === null || $user->isBlocked)
            return false;

        $this->setSessionAs($user);
        return true;
    }

    public function refreshUserContext(): ?UserLoginContextDTO
    {
        $old = self::getLoginContext();
        if ($old === null) {
            if (!empty($_COOKIE['remember_me']))
                if ($this->loginWithRememberMe())
                    return self::getLoginContext();
            return null;
        }

        $qb = UserQuery::asProfile();
        $qb->where(UserCriteria::bySessionFlag(alias: 'u'))
            ->bind(':session_flag', $old->sessionFlag);

        $user = $this->userRepository->getOne($qb);
        if ($user === null || $user->isBlocked) {
            $this->invalidateSession();
            return null;
        }

        $this->setSessionAs($user);
        return self::getLoginContext();
    }

    /**
     * @throws ConflictException
     */
    public function register(UserRegisterDTO $dto): void
    {
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

    /**
     * @throws RandomException
     */
    public function login(UserLoginDTO $dto): LoginResult
    {
        $qb = UserQuery::asProfile();
        $qb->where(UserCriteria::byEmail(alias: 'u'))
            ->bind(':email', $dto->email);
        $user = $this->userRepository->getOne($qb);

        if ($user !== null && $user->totpSecret !== null && strlen($user->totpSecret) !== 0) {
            if ($dto->otp === null || strlen($dto->otp) === 0)
                return LoginResult::TWO_FACTOR_REQUIRED;
            if (!$this->verifyTotp($user->totpSecret, $dto->otp))
                return LoginResult::TWO_FACTOR_REQUIRED;
        }

        if ($user === null || $user->isBlocked) {
            password_verify($dto->password, 'placeholder');
            return LoginResult::INVALID;
        }

        if (!password_verify($dto->password, $user->hashedPassword))
            return LoginResult::INVALID;

        $this->setSessionAs($user, $dto->rememberMe);

        return LoginResult::SUCCESS;
    }

    public function logout(): void
    {
        $this->invalidateSession();
    }

    /**
     * @throws UnauthorizedException
     */
    public function register2FA(OTPRegisterDTO $dto): bool
    {
        $context = $this->getSessionContext();
        if ($context === null)
            throw new UnauthorizedException();

        if (!$this->verifyTotp($dto->secret, $dto->code))
            return false;

        $this->userRepository->setTotpSecret($context->id, $dto->secret);
        return true;
    }

    /**
     * @param non-empty-string $code
     * @throws UnauthorizedException
     * @throws ForbiddenException
     * @throws NotFoundException
     */
    public function unregister2FA(string $code): void
    {
        $context = $this->getSessionContext();
        if ($context === null)
            throw new UnauthorizedException();

        $qb = UserQuery::withMinimalDetails();
        $qb->where(UserCriteria::byId(alias: 'u'))
            ->bind(':id', $context->id);

        $user = $this->userRepository->getOne($qb);
        if ($user === null)
            throw new NotFoundException();

        if ($user->totpSecret === null || strlen($user->totpSecret) === 0)
            return;

        if (!$this->verifyTotp($user->totpSecret, $code))
            throw new ForbiddenException();

        $this->userRepository->setTotpSecret($context->id, null);
    }
}
