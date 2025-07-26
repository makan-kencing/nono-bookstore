<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\Book\Book;
use DateTime;
use PDOStatement;
use Throwable;

use function PHPUnit\Framework\assertNotNull;

/**
 * @extends RowMapper<Book>
 */
readonly class BookRowMapper extends RowMapper
{
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
            $id = $row[$prefix . 'id'];
            $book = $bookMap[$id] ?? null;

            if ($book == null) {
                $book = $this->mapRow($row, prefix: $prefix);
                assertNotNull($book);

                $bookMap[$id] = $book;
            }

            $book->images ??= [];
            if (($id = $row[$prefix . 'images.id']) != null) {
                /** @noinspection PhpArrayIsAlwaysEmptyInspection */
                $bookImage = $book->images[$id] ?? null;
                if ($bookImage == null) {
                    $bookImage = $this->bookImageRowMapper->mapRow($row, prefix: $prefix . 'images.');
                    assertNotNull($bookImage);
                    $bookImage->book = $book;

                    $book->images[$id] = $bookImage;
                }
            }

            $book->authors ??= [];
            if (($id = $row[$prefix . 'authors.author.id']) != null) {
                /** @noinspection PhpArrayIsAlwaysEmptyInspection */
                $author = $book->authors[$id] ?? null;
                if ($author == null) {
                    $author = $this->authorDefinitionRowMapper->mapRow($row, prefix: $prefix . 'authors.');
                    assertNotNull($author);
                    $author->book = $book;

                    $book->authors[$id] = $author;
                }
            }

            $book->categories ??= [];
            if (($id = $row[$prefix . 'categories.category.id']) != null) {
                /** @noinspection PhpArrayIsAlwaysEmptyInspection */
                $category = $book->categories[$id] ?? null;
                if ($category == null) {
                    $category = $this->categoryDefinitionRowMapper->mapRow($row, prefix: $prefix . 'categories.');
                    assertNotNull($category);
                    $category->book = $book;

                    $book->categories[$id] = $category;
                }
            }

            $book->ratings ??= [];
            if (($id = $row[$prefix . 'ratings.id']) != null) {
                /** @noinspection PhpArrayIsAlwaysEmptyInspection */
                $rating = $book->ratings[$id] ?? null;
                if ($rating == null) {
                    $rating = $this->ratingRowMapper->mapRow($row, prefix: $prefix . 'ratings.');
                    assertNotNull($rating);
                    $rating->book = $book;

                    $book->ratings[$id] = $rating;

                    $rating->replies ??= [];
                    if (($id = $row[$prefix . 'ratings.replies.id']) != null) {
                        /** @noinspection PhpArrayIsAlwaysEmptyInspection */
                        $reply = $rating->replies[$id] ?? null;
                        if ($reply == null) {
                            $reply = $this->replyRowMapper->mapRow($row, prefix: $prefix . 'ratings.replies.');
                            assertNotNull($reply);
                            $reply->rating = $rating;

                            $rating->replies[$id] = $reply;
                        }
                    }
                }
            }
        }

        return array_values($bookMap);
    }

    /**
     * @inheritDoc
     */
    public function mapRow(array $row, string $prefix = ''): ?Book
    {
        $id = $row[$prefix . 'id'] ?? null;
        if ($id == null) {
            return null;
        }

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
