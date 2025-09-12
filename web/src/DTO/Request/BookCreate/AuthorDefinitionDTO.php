<?php

declare(strict_types=1);

namespace App\DTO\Request\BookCreate;

use App\DTO\Request\RequestDTO;
use App\Entity\Book\Author\AuthorDefinitionType;
use App\Exception\BadRequestException;
use Throwable;

readonly class AuthorDefinitionDTO extends RequestDTO
{
    public function __construct(
        public int                  $authorId,
        public AuthorDefinitionType $type
    )
    {
    }

    /**
     * @inheritDoc
     */
    public static function jsonDeserialize(mixed $json): self
    {
        assert(is_array($json));

        try {
            return new self(
                (int)$json['id'],
                AuthorDefinitionType::fromName($json['type'])
            );
        } catch (Throwable) {
            throw new BadRequestException();
        }
    }

    /**
     * @inheritDoc
     */
    public function validate(): void
    {
    }
}
