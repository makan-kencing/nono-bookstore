<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\Book\Book;
use DateTime;
use PDOStatement;

/**
 * @extends RowMapper<Book>
 */
readonly class BookRowMapper extends RowMapper
{
    /**
     * @inheritDoc
     */
    public function map(PDOStatement $stmt, string $prefix = '')
    {
        $authorDefinitionRowMapper = new AuthorDefinitionRowMapper();
        $bookImageRowMapper = new BookImageRowMapper();
        $categoryDefinitionRowMapper = new CategoryDefinitionRowMapper();
        $ratingRowMapper = new RatingRowMapper();
        $replyRowMapper = new ReplyRowMapper();

        /** @var array<int, Book> $bookMap */
        $bookMap = [];
        foreach ($stmt as $row) {
            $bookId = $row[$prefix . 'id'];
            $book = $bookMap[$bookId] ?? null;

            if ($book == null) {
                $book = $this->mapRow($row, prefix: $prefix);

                $bookMap[$bookId] = $book;
            }

            $book->images ??= [];
            $bookImageId = $row[$prefix . 'images.id'];
            $bookImage = $book->images[$bookImageId] ?? null;
            if ($bookImage == null) {
                $bookImage = $bookImageRowMapper->mapRow($row, prefix: $prefix . 'images.');

                $book->images[$bookImageId] = $bookImage;
            }

            $book->authors ??= [];
            $authorId = $row[$prefix . 'authors.author.id'];
            $author = $book->authors[$authorId] ?? null;
            if ($author == null) {
                $author = $authorDefinitionRowMapper->mapRow($row, prefix: $prefix . 'authors.');

                $book->authors[$authorId] = $author;
            }

            $book->categories ??= [];
            $categoryId = $row[$prefix . 'categories.category.id'];
            $category = $book->categories[$categoryId] ?? null;
            if ($category == null) {
                $category = $categoryDefinitionRowMapper->mapRow($row, prefix: $prefix . 'categories.');

                $book->categories[$categoryId] = $category;
            }

            $book->ratings ??= [];
            $ratingId = $row[$prefix . 'ratings.id'];
            $rating = $ratingsMap[$ratingId] ?? null;
            if ($rating == null) {
                $rating = $ratingRowMapper->mapRow($row, prefix: $prefix . 'ratings.');

                $rating->replies ??= [];
                $replyId = $row[$prefix . 'ratings.replies.id'];
                $reply = $rating->replies[$replyId] ?? null;
                if ($reply == null) {
                    $reply = $replyRowMapper->mapRow($row, prefix: $prefix . 'ratings.replies.');

                    $rating->replies[$replyId] = $reply;
                }
            }
        }

        return array_values($bookMap);
    }

    /**
     * @inheritDoc
     */
    public function mapRow(mixed $row, string $prefix = '')
    {
        $seriesRepository = new SeriesRowMapper();

        $book = new Book();

        $book->id = $row[$prefix . 'id'];
        $book->slug = $row[$prefix . 'slug'];
        $book->isbn = $row[$prefix . 'isbn'];
        $book->title = $row[$prefix . 'title'];
        $book->description = $row[$prefix . 'description'];
        $book->publisher = $row[$prefix . 'publisher'];
        $book->publishedAt = DateTime::createFromFormat('Y-m-d', $row[$prefix . 'publishedDate']);
        $book->numberOfPages = $row[$prefix . 'numberOfPages'];
        $book->language = $row[$prefix . 'language'];
        $book->dimensions = $row[$prefix . 'dimensions'];
        $book->series = $seriesRepository->mapRow($row, prefix: $prefix . 'series.');

        return $book;
    }
}
