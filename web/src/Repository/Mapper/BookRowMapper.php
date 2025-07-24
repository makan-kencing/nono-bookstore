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
        $authorDefinitionRepository = new AuthorDefinitionRowMapper();
        $authorRepository = new AuthorRowMapper();

        /** @var array<int, Book> $bookMap */
        $bookMap = [];
        $bookImagesMap = [];
        $authorsMap = [];
        $categoriesMap = [];
        $ratingsMap = [];
        $repliesMap = [];
        foreach ($stmt as $row) {
            $bookId = $row[$prefix . 'id'];
            $book = $bookMap[$bookId] ?? null;

            if ($book == null) {
                $book = $this->mapRow($row, prefix: $prefix);

                $bookMap[$bookId] = $book;
            }

            $bookImageId = $row[$prefix . 'images.id'];
            $bookImage = $bookImagesMap[$bookImageId] ?? null;
            if ($bookImage == null) {
            }

            $authorId = $row[$prefix . 'authors.author.id'];
            $author = $authorsMap[$authorId] ?? null;
            if ($author == null) {
                $authorDefinition = $authorDefinitionRepository->mapRow($row, prefix: $prefix . 'authors.');
                $authorDefinition->author = $authorRepository->mapRow($row, prefix: $prefix . 'authors.author.');

                $book->authors ??= [];
                $book->authors[] = $authorDefinition;

                $authorsMap[$authorId] = $authorDefinition;
            }

            $categoryId = $row[$prefix . 'categories.category.id'];
            $category = $categoriesMap[$categoryId] ?? null;
            if ($category == null) {
            }

            $ratingId = $row[$prefix . 'ratings.id'];
            $rating = $ratingsMap[$ratingId] ?? null;
            if ($rating == null) {
            }

            $replyId = $row[$prefix . 'ratings.replies.id'];
            $reply = $repliesMap[$replyId] ?? null;
            if ($reply == null) {
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
