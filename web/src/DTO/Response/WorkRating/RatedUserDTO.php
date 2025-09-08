<?php

declare(strict_types=1);

namespace App\DTO\Response\WorkRating;

use App\DTO\Response\ImageDTO;
use App\DTO\Response\ResponseDTO;
use App\Entity\User\User;

readonly class RatedUserDTO extends ResponseDTO
{
    public function __construct(
        public string $username,
        public ?ImageDTO $image
    ) {
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'username' => $this->username,
            'image' => $this->image
        ];
    }

    public static function fromUser(User $user): self
    {
        return new self(
            $user->username,
            $user->image !== null ? ImageDTO::fromFile($user->image) : null
        );
    }

}
