<?php

declare(strict_types=1);

namespace App\Service;

use App\Core\Clock;
use App\Core\View;
use App\DTO\Request\OTPRegisterDTO;
use App\DTO\Request\UserLoginDTO;
use App\DTO\Request\UserRegisterDTO;
use App\DTO\Response\OTPGenerateDTO;
use App\DTO\Response\UserTokenGenerateDTO;
use App\DTO\UserLoginContextDTO;
use App\Entity\User\User;
use App\Entity\User\UserRole;
use App\Entity\User\UserToken;
use App\Entity\User\UserTokenType;
use App\Exception\ConflictException;
use App\Exception\ForbiddenException;
use App\Exception\NotFoundException;
use App\Exception\UnauthorizedException;
use App\Mailer\MailService;
use App\Repository\Query\UserCriteria;
use App\Repository\Query\UserQuery;
use App\Repository\Query\UserTokenCriteria;
use App\Repository\Query\UserTokenQuery;
use App\Repository\UserRepository;
use App\Repository\UserTokenRepository;
use OTPHP\TOTP;
use PDO;
use Psr\Clock\ClockInterface;
use UnexpectedValueException;

readonly class AuthService extends Service
{
    private UserRepository $userRepository;
    private UserTokenRepository $userTokenRepository;
    private MailService $mailService;
    private UserService $userService;
    private ClockInterface $clock;

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        $this->userRepository = new UserRepository($pdo);
        $this->userTokenRepository = new UserTokenRepository($pdo);
        $this->mailService = new MailService();
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

    public function invalidateGlobalSession(User|int $user): void
    {
        if ($user instanceof User) $user = $user->id ?? throw new UnexpectedValueException();

        $this->userRepository->resetSession($user);
        $this->userTokenRepository->deleteByUserAndType($user, UserTokenType::REMEMBER_ME);
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

    public function setSessionAs(User $user, bool $rememberMe = false): void
    {
        $_SESSION['user'] = UserLoginContextDTO::fromUser($user);

        if ($rememberMe) {
            $token = $this->createRememberMeToken($user);
            $cookieValue = $token->selector . ':' . $token->token;
            setcookie('remember_me', $cookieValue, [
                'expires' => time() + 30 * 24 * 60 * 60,
                'path' => '/',
                'httponly' => true,
                'secure' => true,
                'samesite' => 'Strict'
            ]);
        }
    }

    public function createRememberMeToken(User $user): UserToken
    {
        assert($user->id !== null);
        $this->userTokenRepository->deleteByUserAndType($user->id, UserTokenType::REMEMBER_ME);

        $tokenData = UserTokenGenerateDTO::generate('P30D');

        $token = $tokenData->toUserToken($user, UserTokenType::REMEMBER_ME);
        $this->userTokenRepository->createToken($token);

        return $token;
    }

    public function getTokenBySelector(string $selector, UserTokenType $type): ?UserToken
    {
        $qb = UserTokenQuery::withMinimalDetails();
        $qb->where(UserTokenCriteria::bySelector()
            ->and(UserTokenCriteria::byType())
            ->and(UserTokenCriteria::notExpired()))
            ->bind(':selector', $selector)
            ->bind(':type', $type->name);
        return $this->userTokenRepository->getOne($qb);
    }

    public function loginWithRememberMe(): bool
    {
        [$selector, $token] = explode(':', $_COOKIE['remember_me']);
        $userToken = $this->getTokenBySelector($selector, UserTokenType::REMEMBER_ME);

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
     * @param string $email
     * @return void
     */
    public function requestResetPassword(string $email): void
    {
        $qb = UserQuery::withMinimalDetails();
        $qb->where(UserCriteria::byEmail())
            ->bind(':email', $email);

        $user = $this->userRepository->getOne($qb);
        if ($user === null)
            return;

        assert($user->id !== null);
        $this->userTokenRepository->deleteByUserAndType($user->id, UserTokenType::RESET_PASSWORD);

        $tokenData = UserTokenGenerateDTO::generate('PT1H');

        $token = $tokenData->toUserToken($user, UserTokenType::RESET_PASSWORD);
        $this->userTokenRepository->createToken($token);

        $url = $this->getSiteUrl() . "/reset-password?selector=$token->selector&token=$tokenData->token";

        $subject = 'Reset your password';
        $body = View::render('email/reset_password.php', ['user' => $user, 'url' => $url]);
        $this->mailService->sendMail($user->email, $subject, $body);
    }

    /**
     * @throws ForbiddenException
     * @throws UnauthorizedException
     * @throws NotFoundException
     */
    public function changePassword(int $userId, string $oldPassword, string $newPassword): void
    {
        $context = $this->getSessionContext();
        if ($context === null)
            throw new UnauthorizedException();

        $user = $this->userService->getPlainUser($userId);
        if ($user === null)
            throw new NotFoundException();

        if (!password_verify($oldPassword, $user->hashedPassword))
            throw new UnauthorizedException();

        if (!$this->userService->canModify($context, $user))
            throw new ForbiddenException();

        $user->hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $this->userRepository->update($user);
        $this->invalidateGlobalSession($user);
    }

    /**
     * Reset password using selector + token.
     *
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function resetPassword(string $selector, string $token, string $newPassword): void
    {
        $userToken = $this->getTokenBySelector($selector, UserTokenType::RESET_PASSWORD);
        if ($userToken === null)
            throw new NotFoundException;

        assert($userToken->id !== null);
        assert($userToken->user->id !== null);
        if (!password_verify($token, $userToken->token))
            throw new UnauthorizedException();

        $user = $this->userService->getPlainUser($userToken->user->id);
        if ($user === null)
            throw new NotFoundException;

        assert($user->id !== null);
        $user->hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $this->userRepository->update($user);
        $this->invalidateGlobalSession($user);

        $this->userTokenRepository->deleteById($userToken->id);
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
