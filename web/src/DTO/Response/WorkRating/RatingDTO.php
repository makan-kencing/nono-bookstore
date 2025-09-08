<?php

declare(strict_types=1);

namespace App\DTO\Response\WorkRating;

use App\DTO\Response\ResponseDTO;
use App\Entity\Rating\Rating;
use DateTime;

readonly class RatingDTO extends ResponseDTO
{
    public function __construct(
        public int $id,
        public RatedUserDTO $user,
        public int $rating,
        public string $title,
        public string $content,
        public DateTime $ratedAt
    ) {
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'user' => $this->user,
            'rating' => $this->rating,
            'title' => $this->title,
            'content' => $this->content,
            'rated_at' => $this->ratedAt
        ];
    }

    public static function fromRating(Rating $rating): self
    {
        return new self(
            $rating->id,
            RatedUserDTO::fromUser($rating->user),
            $rating->rating,
            $rating->title,
            $rating->content,
            $rating->ratedAt
        );
    }
}
