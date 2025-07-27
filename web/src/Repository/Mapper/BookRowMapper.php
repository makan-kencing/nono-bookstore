<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\Book\Author\AuthorDefinition;
use App\Entity\Book\Book;
use App\Entity\Book\BookImage;
use App\Entity\Book\Category\CategoryDefinition;
use App\Entity\Rating\Rating;
use App\Entity\Rating\Reply;
use DateTime;
use PDOStatement;
use Throwable;

/**
 * @extends RowMapper<Book>
 */
readonly class BookRowMapper extends RowMapper
{
    public const string IMAGES = 'images.';
    public const string AUTHORS = 'authors.';
    public const string CATEGORIES = 'categories.';
    public const string RATINGS = 'ratings.';

    private AuthorDefinitionRowMapper $authorDefinitionRowMapper;
    private BookImageRowMapper $bookImageRowMapper;
    private CategoryDefinitionRowMapper $categoryDefinitionRowMapper;
    private RatingRowMapper $ratingRowMapper;
    private ReplyRowMapper $replyRowMapper;
    private SeriesRowMapper $seriesRowMapper;

    public function __construct()
    {
        $this->authorDefinitionRowMapper = new AuthorDefinitionRowMapper();
        $this->bookImageRowMapper = new BookImageRowMapper();
        $this->categoryDefinitionRowMapper = new CategoryDefinitionRowMapper();
        $this->ratingRowMapper = new RatingRowMapper();
        $this->replyRowMapper = new ReplyRowMapper();
        $this->seriesRowMapper = new SeriesRowMapper();
    }

    /**
     * @inheritDoc
     */
    public function map(PDOStatement $stmt, string $prefix = ''): array
    {
        /** @var array<int, Book> $bookMap */
        $bookMap = [];
        foreach ($stmt as $row) {
            $id = $row[$prefix . self::ID];
            $book = $bookMap[$id] ?? null;

            if ($book == null) {
                $book = $this->mapRow($row, prefix: $prefix);

                $bookMap[$id] = $book;
            }

            self::mapOneToMany(
                $row,
                $book->images,
                $this->bookImageRowMapper,
                prefix: $prefix . self::IMAGES,
                backreference: function (BookImage $image) use ($book) {
                    $image->book = $book;
                }
            );

            self::mapOneToMany(
                $row,
                $book->authors,
                $this->authorDefinitionRowMapper,
                prefix: $prefix . self::AUTHORS,
                backreference: function (AuthorDefinition $author) use ($book) {
                    $author->book = $book;
                }
            );

            self::mapOneToMany(
                $row,
                $book->categories,
                $this->categoryDefinitionRowMapper,
                prefix: $prefix . self::CATEGORIES,
                backreference: function (CategoryDefinition $category) use ($book) {
                    $category->book = $book;
                }
            );

            self::mapOneToMany(
                $row,
                $book->ratings,
                $this->ratingRowMapper,
                prefix: $prefix . self::RATINGS,
                backreference: function (Rating $rating) use ($row, $book, $prefix) {
                    $rating->book = $book;

                    $this->ratingRowMapper::mapOneToMany(
                        $row,
                        $rating->replies,
                        $this->replyRowMapper,
                        prefix: $prefix . self::RATINGS . RatingRowMapper::REPLIES,
                        backreference: function (Reply $reply) use ($rating) {
                            $reply->rating = $rating;
                        }
                    );
                }
            );
        }

        return array_values($bookMap);
    }

    /**
     * @inheritDoc
     */
    public function mapRow(array $row, string $prefix = ''): Book
    {
        $id = $row[$prefix . 'id'];
        $book = new Book();
        $book->id = $id;
        try {
            $book->slug = $row[$prefix . 'slug'];
            $book->isbn = $row[$prefix . 'isbn'];
            $book->title = $row[$prefix . 'title'];
            $book->description = $row[$prefix . 'description'];
            $book->publisher = $row[$prefix . 'publisher'];
            $book->publishedAt = DateTime::createFromFormat('Y-m-d', $row[$prefix . 'publishedDate']);
            $book->numberOfPages = $row[$prefix . 'numberOfPages'];
            $book->language = $row[$prefix . 'language'];
            $book->dimensions = $row[$prefix . 'dimensions'];
            $book->series = $this->seriesRowMapper->mapRow($row, prefix: $prefix . 'series.');
        } catch (Throwable $e) {
            if (!$this->isInvalidArrayAccess($e)) {
                throw $e;
            }

            $book = new Book();
            $book->id = $id;
            $book->isLazy = true;
        }

        return $book;
    }
}
