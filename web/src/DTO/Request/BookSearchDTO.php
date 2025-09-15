<?php

declare(strict_types=1);

namespace App\DTO\Request;

use App\Entity\Product\CoverType;
use App\Exception\BadRequestException;
use App\Exception\UnprocessableEntityException;
use App\Orm\Expr\OrderDirection;
use App\Utils\EnumUtils;
use Throwable;

enum BookSearchSortOption: int
{
    use EnumUtils;

    case RELEVANCE = 0;
    case PRICE_ASC = 1;
    case PRICE_DESC = 2;
    case TITLE_ASC = 3;
    case TITLE_DESC = 4;
    case PUBLISHED_ASC = 5;
    case PUBLISHED_DESC = 6;

    public function asLabel(): string
    {
        return match ($this) {
            self::RELEVANCE => 'Relevance',
            self::PRICE_ASC => 'Price (low to high)',
            self::PRICE_DESC => 'Price (high to low)',
            self::TITLE_ASC => 'Title (A - Z)',
            self::TITLE_DESC => 'Title (Z - A)',
            self::PUBLISHED_ASC => 'Publication Date (old to new)',
            self::PUBLISHED_DESC => 'Publication Date (new to old)',
        };
    }

    public function getDirection(): OrderDirection
    {
        return match ($this) {
            self::RELEVANCE, self::PRICE_ASC, self::TITLE_ASC, self::PUBLISHED_ASC => OrderDirection::ASCENDING,
            self::PRICE_DESC, self::TITLE_DESC, self::PUBLISHED_DESC => OrderDirection::DESCENDING,
        };
    }
}

readonly class BookSearchDTO extends SearchDTO
{
    public function __construct(
        ?string                      $query = null,
        public ?CoverType            $format = null,
        public ?int                  $categoryId = null,
        public ?int                  $minPrice = null,
        public ?int                  $maxPrice = null,
        public ?int                  $authorId = null,
        public ?string               $publisher = null,
        public ?string               $language = null,
        public ?BookSearchSortOption $option = null,
        int                          $page = 1,
        int                          $pageSize = 50
    )
    {
        parent::__construct($query, $page, $pageSize);
    }

    public function withPage(int $page): BookSearchDTO
    {
        return new self(
            $this->query,
            $this->format,
            $this->categoryId,
            $this->minPrice,
            $this->maxPrice,
            $this->authorId,
            $this->publisher,
            $this->language,
            $this->option,
            $page,
            $this->pageSize,
        );
    }

    public function withPageSize(int $pageSize): BookSearchDTO
    {
        return new self(
            $this->query,
            $this->format,
            $this->categoryId,
            $this->minPrice,
            $this->maxPrice,
            $this->authorId,
            $this->publisher,
            $this->language,
            $this->option,
            $this->page,
            $pageSize,
        );
    }

    public function withCategoryId(int $categoryId): BookSearchDTO
    {
        return new self(
            $this->query,
            $this->format,
            $categoryId,
            $this->minPrice,
            $this->maxPrice,
            $this->authorId,
            $this->publisher,
            $this->language,
            $this->option,
            $this->page,
            $this->pageSize,
        );
    }

    public function withCoverType(CoverType $coverType): BookSearchDTO
    {
        return new self(
            $this->query,
            $coverType,
            $this->categoryId,
            $this->minPrice,
            $this->maxPrice,
            $this->authorId,
            $this->publisher,
            $this->language,
            $this->option,
            $this->page,
            $this->pageSize,
        );
    }

    public function withPriceRange(int $minPrice, int $maxPrice): BookSearchDTO
    {
        return new self(
            $this->query,
            $this->format,
            $this->categoryId,
            $minPrice,
            $maxPrice,
            $this->authorId,
            $this->publisher,
            $this->language,
            $this->option,
            $this->page,
            $this->pageSize,
        );
    }

    /**
     * @inheritDoc
     */
    public static function jsonDeserialize(mixed $json): BookSearchDTO
    {
        try {
            return new self(
                $json['query'] ?? null,
                    CoverType::tryFromName($json['format'] ?? null),
                    self::toInt($json['category_id'] ?? null),
                    array_key_exists('min_price', $json) && !empty($json['min_price']) ? ((int) $json['min_price']) * 100 : null,
                    array_key_exists('max_price', $json) && !empty($json['max_price']) ? ((int) $json['max_price']) * 100 : null,
                    self::toInt($json['author_id'] ?? null),
                    $json['publisher'] ?? null,
                $json['language'] ?? null,
                    BookSearchSortOption::tryFromName($json['sort'] ?? null),
                    self::toInt($json['page'] ?? 1),
                    self::toInt($json['page_size'] ?? 50)
            );
        } catch (Throwable $e) {
            throw new BadRequestException();
        }
    }

    /**
     * @inheritDoc
     */
    public function validate(): void
    {
    }

    public function toQueryString(): string
    {
        $params = [];
        if ($this->query !== null)
            $params['query'] = $this->query;

        if ($this->format !== null)
            $params['format'] = $this->format->name;

        if ($this->categoryId !== null)
            $params['category_id'] =  $this->categoryId;

        if ($this->minPrice !== null)
            $params['min_price'] = $this->minPrice;

        if ($this->maxPrice !== null)
            $params['max_price'] = $this->maxPrice;

        if ($this->authorId !== null)
            $params['author_id'] = $this->authorId;

        if ($this->publisher !== null)
            $params['publisher'] = $this->publisher;

        if ($this->language !== null)
            $params['language'] = $this->language;

        if ($this->option !== null)
            $params['option'] = $this->option->name;

        $params['page'] = $this->page;
        $params['page_size'] = $this->pageSize;

        return '?' . http_build_query($params);
    }

    public function isIsbnQuery(): bool
    {
        if ($this->query !== null)
            return strlen($this->query) == 13 && ctype_digit($this->query);
        return false;
    }
}
