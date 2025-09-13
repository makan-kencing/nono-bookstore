<?php

declare(strict_types=1);

namespace App\DTO\Request\BookCreate;

use App\DTO\Request\RequestDTO;
use App\Entity\Book\Book;
use App\Entity\Book\Work;
use App\Entity\Product\CoverType;
use App\Exception\BadRequestException;
use App\Exception\UnprocessableEntityException;
use Throwable;

readonly class BookCreateDTO extends RequestDTO
{
    /**
     * @param int $workId
     * @param string $isbn
     * @param ?string $description
     * @param CoverType $coverType
     * @param positive-int $numberOfPages
     * @param ?string $dimensions
     * @param ?string $language
     * @param ?string $editionInformation
     * @param string $publisher
     * @param string $publicationDate
     * @param AuthorDefinitionDTO[] $authors
     * @param array<string, positive-int> $initialStocks
     * @param positive-int $initialPrice
     * @param ?positive-int $initialCost
     */
    public function __construct(
        public int       $workId,
        public string    $isbn,
        public ?string   $description,
        public CoverType $coverType,
        public int       $numberOfPages,
        public ?string   $dimensions,
        public ?string   $language,
        public ?string   $editionInformation,
        public string    $publisher,
        public string    $publicationDate,
        public array $authors,
        public array $initialStocks,
        public int   $initialPrice,
        public ?int  $initialCost,
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
                (int)$json['work']['id'],
                $json['isbn'],
                $json['description'] ?: null,
                CoverType::fromName($json['cover_type']),
                (int)$json['number_of_pages'],
                $json['dimensions'] ?: null,
                $json['language'] ?: null,
                $json['edition_information'] ?: null,
                $json['publisher'],
                $json['publication_date'],
                array_map(
                    fn($json) => AuthorDefinitionDTO::jsonDeserialize($json),
                    $json['authors'],
                ),
                $json['initial_stocks'],
                (int) ($json['initial_price'] * 100),
                $json['initial_cost'] ?? null ? (int) ($json['initial_cost'] * 100) : null
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
        if (strlen($this->isbn) != 13 || !ctype_digit($this->isbn))
            $rules[] = [
                "field" => "isbn",
                "type" => "isbn",
                "reason" => "Invalid isbn format. Needs to be 13 digits long."
            ];

        if ($this->numberOfPages < 1)
            $rules[] = [
                "field" => "number_of_pages",
                "type" => "length",
                "reason" => "Must be 1 or more pages"
            ];

        if ($this->initialPrice < 0)
            $rules[] = [
                "field" => "initial_price",
                "type" => "price",
                "reason" => "Initial price can't be negative."
            ];

        if ($this->initialCost && $this->initialCost < 0)
            $rules[] = [
                "field" => "initial_cost",
                "type" => "cost",
                "reason" => "Initial cost can't be negative."
            ];

        if ($rules)
            throw new UnprocessableEntityException($rules);
    }

    public function toBook(): Book
    {
        $book = new Book();

        $book->work = new Work();
        $book->work->id = $this->workId;
        $book->isbn = $this->isbn;
        $book->description = $this->description;
        $book->coverType = $this->coverType;
        $book->numberOfPages = $this->numberOfPages;
        $book->dimensions = $this->dimensions;
        $book->language = $this->language;
        $book->editionInformation = $this->editionInformation;
        $book->publisher = $this->publisher;
        $book->publicationDate = $this->publicationDate;
        $book->authors = array_map(
            fn ($dto) => $dto->toAuthorDefinition($book),
            $this->authors
        );
        $book->deletedAt = null;

        return $book;
    }
}
