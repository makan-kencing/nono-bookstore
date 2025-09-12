<?php

declare(strict_types=1);

namespace App\DTO\Request\BookCreate;

use App\DTO\Request\RequestDTO;
use App\Entity\Product\CoverType;
use App\Exception\BadRequestException;
use App\Exception\UnprocessableEntityException;
use Throwable;

readonly class BookCreateDTO extends RequestDTO
{
    /**
     * @param int $workId
     * @param string $isbn
     * @param string|null $description
     * @param CoverType $coverType
     * @param int $numberOfPages
     * @param string|null $dimensions
     * @param string|null $language
     * @param string|null $editionInformation
     * @param string $publisher
     * @param string $publicationDate
     * @param AuthorDefinitionDTO[] $authors
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
        public array     $authors
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
                )
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

        if ($rules)
            throw new UnprocessableEntityException($rules);
    }
}
