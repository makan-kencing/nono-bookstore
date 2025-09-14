<?php

declare(strict_types=1);

namespace App\DTO\Response;

use App\Entity\User\User;
use App\Entity\User\UserToken;
use App\Entity\User\UserTokenType;
use DateInterval;
use DateTime;
use RuntimeException;
use Throwable;

readonly class UserTokenGenerateDTO extends ResponseDTO
{
    public function __construct(
        public string $selector,
        public string $token,
        public string $hashedToken,
        public DateTime $expiresAt,
    ) {
    }

    public static function generate(string $expiryInterval): UserTokenGenerateDTO
    {
        try {
            $selector = bin2hex(random_bytes(6));
            $token = bin2hex(random_bytes(32));

            return new self(
                $selector,
                $token,
                password_hash($token, PASSWORD_DEFAULT),
                (new DateTime())->add(new DateInterval($expiryInterval))
            );
        } catch (Throwable) {
            throw new RuntimeException();
        }
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'selector' => $this->selector,
            'token' => $this->token,
            'hashedToken' => $this->hashedToken,
            'expiresAt' => $this->expiresAt,
        ];
    }

    public function toUserToken(User $user, UserTokenType $type): UserToken
    {
        $token = new UserToken();
        $token->user = $user;
        $token->type = $type;
        $token->selector = $this->selector;
        $token->token = $this->hashedToken;
        $token->expiresAt = $this->expiresAt;

        return $token;
    }
}
