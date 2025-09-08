<?php

declare(strict_types=1);

namespace App\DTO\Response\WorkRating;


use App\DTO\Response\ResponseDTO;
use App\Entity\Rating\Reply;
use DateTime;

readonly class ReplyDTO extends ResponseDTO
{
    public function __construct(
        public RatedUserDTO $user,
        public string $content,
        public DateTime $repliedAt
    ) {
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        return [
            'user' => $this->user,
            'content' => $this->content,
            'replied_at' => $this->repliedAt
        ];
    }

    public static function fromReply(Reply $reply): self
    {
        return new self(
            RatedUserDTO::fromUser($reply->user),
            $reply->content,
            $reply->repliedAt
        );
    }
}
