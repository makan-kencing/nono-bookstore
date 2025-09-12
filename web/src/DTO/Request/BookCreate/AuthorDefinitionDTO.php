<?php

declare(strict_types=1);

namespace App\DTO\Request\BookCreate;

use App\DTO\Request\RequestDTO;
use App\Entity\Book\Author\Author;
use App\Entity\Book\Author\AuthorDefinition;
use App\Entity\Book\Author\AuthorDefinitionType;
use App\Entity\Book\Book;
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

    public function toAuthorDefinition(Book $book): AuthorDefinition
    {
        $ad = new AuthorDefinition();
        $ad->book = $book;
        $ad->author = new Author();
        $ad->author->id = $this->authorId;
        $ad->type = $this->type;
        $ad->comment = null;

        return $ad;
    }
}
