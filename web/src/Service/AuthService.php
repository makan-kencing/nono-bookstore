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
use App\Exception\ConflictException;
use App\Exception\UnauthorizedException;
use App\Repository\Query\UserCriteria;
use App\Repository\Query\UserQuery;
use App\Repository\UserRepository;
use OTPHP\TOTP;
use PDO;
use Psr\Clock\ClockInterface;

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
    }

    public function setSessionAs(User $user): void
    {
        $_SESSION['user'] = UserLoginContextDTO::fromUser($user);
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

    public function refreshUserContext(): ?UserLoginContextDTO
    {
        // TODO: check remember me

        $old = self::getLoginContext();
        if ($old === null)
            return null;

        $user = $this->userService->getUserForProfile($old->id);
        if ($user === null || $user->isBlocked) {
            $this->invalidateSession();
            return null;
        }

        $this->setSessionAs($user);
        return self::getLoginContext();
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

        // prevent timing attack even if its a failed login
        if ($user === null || $user->isBlocked) {
            password_verify($dto->password, 'placeholder');
            return LoginResult::INVALID;
        }

        if (!password_verify($dto->password, $user->hashedPassword))
            return LoginResult::INVALID;


        // TODO: implement remember me tokens

        // TODO: log the login success / failure event

        $this->setSessionAs($user);
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
}
