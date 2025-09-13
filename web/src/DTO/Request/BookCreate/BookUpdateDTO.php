<?php

declare(strict_types=1);

namespace App\DTO\Request\BookCreate;

use App\DTO\Request\RequestDTO;
use App\Entity\Book\Book;
use App\Entity\Product\CoverType;
use App\Exception\BadRequestException;
use App\Exception\UnprocessableEntityException;
use Throwable;

readonly class BookUpdateDTO extends RequestDTO
{
    /**
     * @param int $id
     * @param ?int $workId
     * @param ?string $isbn
     * @param ?string $description
     * @param ?CoverType $coverType
     * @param ?int $numberOfPages
     * @param ?string $dimensions
     * @param ?string $language
     * @param ?string $editionInformation
     * @param ?string $publisher
     * @param ?string $publicationDate
     * @param ?AuthorDefinitionDTO[] $authors
     */
    public function __construct(
        public int        $id,
        public ?int       $workId,
        public ?string    $description,
        public ?CoverType $coverType,
        public ?int       $numberOfPages,
        public ?string    $dimensions,
        public ?string    $language,
        public ?string    $editionInformation,
        public ?string    $publisher,
        public ?string    $publicationDate
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
                (int)($json['work[id]'] ?? null),
                $json['description'] ?? null,
                CoverType::tryFromName($json['cover_type']),
                (int)($json['number_of_pages'] ?? null),
                $json['dimensions'] ?? null,
                $json['language'] ?? null,
                $json['edition_information'] ?? null,
                $json['publisher'] ?? null,
                $json['publication_date'] ?? null
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
        $rules = [];
        if ($this->numberOfPages < 1)
            $rules[] = [
                "field" => "number_of_pages",
                "type" => "length",
                "reason" => "Must be 1 or more pages"
            ];

        if ($rules)
            throw new UnprocessableEntityException($rules);
    }

    public function update(Book $book): Book
    {
        if ($this->workId !== null)
            $book->work->id = $this->workId;
        if ($this->description !== null)
            $book->description = $this->description;
        if ($this->coverType !== null)
            $book->coverType = $this->coverType;
        if ($this->numberOfPages !== null)
            $book->numberOfPages = $this->numberOfPages;
        if ($this->dimensions !== null)
            $book->dimensions = $this->dimensions;
        if ($this->language !== null)
            $book->language = $this->language;
        if ($this->editionInformation !== null)
            $book->editionInformation = $this->editionInformation;
        if ($this->publisher !== null)
            $book->publisher = $this->publisher;
        if ($this->publicationDate !== null)
            $book->publicationDate = $this->publicationDate;

        return $book;
    }
}
