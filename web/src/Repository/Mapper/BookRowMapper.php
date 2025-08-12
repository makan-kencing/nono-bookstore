<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\Book\Author\AuthorDefinition;
use App\Entity\Book\Book;
use App\Entity\Book\BookImage;
use App\Entity\Book\Category\CategoryDefinition;
use App\Entity\Rating\Rating;
use DateTime;
use OutOfBoundsException;

/**
 * @extends RowMapper<Book>
 */
class BookRowMapper extends RowMapper
{
    public const string SLUG = 'slug';
    public const string ISBN = 'isbn';
    public const string TITLE = 'title';
    public const string DESCRIPTION = 'description';
    public const string PUBLISHER = 'publisher.';
    public const string PUBLISHED_DATE = 'publishedDate';
    public const string SERIES_POSITION = 'seriesPosition';
    public const string NUMBER_OF_PAGES = 'numberOfPages';
    public const string LANGUAGE = 'language';
    public const string DIMENSION = 'dimensions';
    public const string IMAGES = 'images.';
    public const string AUTHORS = 'authors.';
    public const string CATEGORIES = 'categories.';
    public const string RATINGS = 'ratings.';
    public const string SERIES = 'series.';

    /**
     * @inheritDoc
     */
    public function mapRow(array $row): Book
    {
        $id = $this->getColumn($row, self::ID);
        if (!is_int($id)) {
            throw new OutOfBoundsException();
        }

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
        $object->publisher = $this->useMapper(PublisherRowMapper::class, self::PUBLISHER)->mapRow($row);
        $object->publishedDate = $this->getColumn($row, self::PUBLISHED_DATE);
        $object->numberOfPages = $this->getColumn($row, self::NUMBER_OF_PAGES);
        $object->language = $this->getColumn($row, self::LANGUAGE);
        $object->dimensions = $this->getColumn($row, self::DIMENSION);
        $object->series = $this->useMapper(SeriesDefinitionRowMapper::class, self::SERIES)->mapRowOrNull($row);
    }

    /**
     * @inheritDoc
     */
    public function bindOneToManyProperties(mixed $object, array $row): void
    {
        $object->images ??= [];
        $this->useMapper(BookImageRowMapper::class, self::IMAGES)->mapOneToMany(
            $row,
            $object->images,
            backreference: function (BookImage $image) use ($object) {
                $image->book = $object;
            }
        );
        $object->authors ??= [];
        $this->useMapper(AuthorDefinitionRowMapper::class, self::AUTHORS)->mapOneToMany(
            $row,
            $object->authors,
            backreference: function (AuthorDefinition $author) use ($object) {
                $author->book = $object;
            }
        );
        $object->categories ??= [];
        $this->useMapper(CategoryDefinitionRowMapper::class, self::CATEGORIES)->mapOneToMany(
            $row,
            $object->categories,
            backreference: function (CategoryDefinition $category) use ($object) {
                $category->book = $object;
            }
        );
        $object->ratings ??= [];
        $this->useMapper(RatingRowMapper::class, self::RATINGS)->mapOneToMany(
            $row,
            $object->ratings,
            backreference: function (Rating $rating) use ($object) {
                $rating->book = $object;
            }
        );
    }
}
