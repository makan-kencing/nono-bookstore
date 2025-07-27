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
use OutOfBoundsException;
use PDOStatement;

/**
 * @extends RowMapper<Book>
 */
class BookRowMapper extends RowMapper
{
    public const string SLUG = 'slug';
    public const string ISBN = 'isbn';
    public const string TITLE = 'title';
    public const string DESCRIPTION = 'description';
    public const string PUBLISHER = 'publisher';
    public const string PUBLISHED_AT = 'publishedAt';
    public const string NUMBER_OF_PAGES = 'numberOfPages';
    public const string LANGUAGE = 'language';
    public const string DIMENSION = 'dimensions';
    public const string IMAGES = 'images.';
    public const string AUTHORS = 'authors.';
    public const string CATEGORIES = 'categories.';
    public const string RATINGS = 'ratings.';
    public const string SERIES = 'series.';

    private AuthorDefinitionRowMapper $authorDefinitionRowMapper;
    private BookImageRowMapper $bookImageRowMapper;
    private CategoryDefinitionRowMapper $categoryDefinitionRowMapper;
    private RatingRowMapper $ratingRowMapper;
    private SeriesRowMapper $seriesRowMapper;

    public function getAuthorDefinitionRowMapper(): AuthorDefinitionRowMapper
    {
        $this->authorDefinitionRowMapper ??= new AuthorDefinitionRowMapper($this->prefix . self::AUTHORS);
        return $this->authorDefinitionRowMapper;
    }

    public function getBookImageRowMapper(): BookImageRowMapper
    {
        $this->bookImageRowMapper ??= new BookImageRowMapper($this->prefix . self::IMAGES);
        return $this->bookImageRowMapper;
    }

    public function getCategoryDefinitionRowMapper(): CategoryDefinitionRowMapper
    {
        $this->categoryDefinitionRowMapper ??= new CategoryDefinitionRowMapper($this->prefix . self::CATEGORIES);
        return $this->categoryDefinitionRowMapper;
    }

    public function getRatingRowMapper(): RatingRowMapper
    {
        $this->ratingRowMapper ??= new RatingRowMapper($this->prefix . self::RATINGS);
        return $this->ratingRowMapper;
    }

    public function getSeriesRowMapper(): SeriesRowMapper
    {
        $this->seriesRowMapper ??= new SeriesRowMapper($this->prefix . self::SERIES);
        return $this->seriesRowMapper;
    }

    /**
     * @inheritDoc
     */
    public function map(PDOStatement $stmt): array
    {
        /** @var array<int, Book> $map */
        $map = [];
        foreach ($stmt as $row) {
            $this->mapOneToMany(
                $row,
                $map,
                nested: [  // maps the one-to-many
                    function (Book $book) use ($row) {
                        $this->getBookImageRowMapper()->mapOneToMany(
                            $row,
                            $book->images,
                            backreference: function (BookImage $image) use ($book) {
                                $image->book = $book;
                            }
                        );
                    },
                    function (Book $book) use ($row) {
                        $this->getAuthorDefinitionRowMapper()->mapOneToMany(
                            $row,
                            $book->authors,
                            backreference: function (AuthorDefinition $author) use ($book) {
                                $author->book = $book;
                            }
                        );
                    },
                    function (Book $book) use ($row) {
                        $this->getCategoryDefinitionRowMapper()->mapOneToMany(
                            $row,
                            $book->categories,
                            backreference: function (CategoryDefinition $category) use ($book) {
                                $category->book = $book;
                            }
                        );
                    },
                    function (Book $book) use ($row) {
                        $this->getRatingRowMapper()->mapOneToMany(
                            $row,
                            $book->ratings,
                            backreference: function (Rating $rating) use ($book) {
                                $rating->book = $book;
                            },
                            nested: [
                                function (Rating $rating) use ($row) {
                                    $this->getRatingRowMapper()->getReplyRowMapper()->mapOneToMany(
                                        $row,
                                        $rating->replies,
                                        backreference: function (Reply $reply) use ($rating) {
                                            $reply->rating = $rating;
                                        }
                                    );
                                }
                            ]
                        );
                    }
                ]
            );
        }

        return array_values($map);
    }

    /**
     * @inheritDoc
     */
    public function mapRow(array $row): Book
    {
        $id = $row[$this->prefix . self::ID];
        assert(is_int($id));

        try {
            $book = new Book();
            $book->id = $id;
            $this->bindProperties($book, $row);
        } catch (OutOfBoundsException) {
            $book = new Book();
            $book->id = $id;
            $book->isLazy = true;
        }

        return $book;
    }

    /**
     * @inheritDoc
     */
    public function bindProperties(mixed $object, array $row): void
    {
        $object->slug = $this->getColumn($row, self::SLUG);
        $object->isbn = $this->getColumn($row, self::ISBN);
        $object->title = $this->getColumn($row, self::TITLE);
        $object->description = $this->getColumn($row, self::DESCRIPTION);
        $object->publisher = $this->getColumn($row, self::PUBLISHER);
        $object->publishedAt = DateTime::createFromFormat(
            'Y-m-d',
            $this->getColumn($row, self::PUBLISHED_AT)
        );
        $object->numberOfPages = $this->getColumn($row, self::NUMBER_OF_PAGES);
        $object->language = $this->getColumn($row, self::LANGUAGE);
        $object->dimensions = $this->getColumn($row, self::DIMENSION);
        $this->getSeriesRowMapper()->mapOneToOne($row, $object->series);
    }
}
