<?php

declare(strict_types=1);

namespace App\Repository\Query;

use App\Entity\Book\Book;
use PDO;
use PDOStatement;

/**
 * @extends Query<Book>
 */
class QueryBookWithFullDetail extends Query
{
    public string $isbn;

    #[\Override] public function createQuery(PDO $pdo): PDOStatement
    {
        $stmt = $pdo->prepare('
            SELECT b.id              `id`,
                   b.slug            `slug`,
                   b.isbn            `isbn`,
                   b.title           `title`,
                   b.description     `description`,
                   b.publisher       `publisher`,
                   b.published_date  `publishedDate`,
                   b.number_of_pages `numberOfPages`,
                   b.language        `language`,
                   b.dimensions      `dimensions`,
                   bi.id             `images.id`,
                   bi.image_url      `images.image_url`,
                   a.id              `authors.author.id`,
                   a.slug            `authors.author.slug`,
                   a.name            `authors.author.name`,
                   a.description     `authors.author.description`,
                   ad.type           `authors.type`,
                   ad.comment        `authors.comment`,
                   c.id              `categories.category.id`,
                   c.slug            `categories.category.slug`,
                   c.name            `categories.category.name`,
                   c.description     `categories.category.description`,
                   cd.is_primary     `categories.isPrimary`,
                   cd.comment        `categories.comment`,
                   cd.from_date      `categories.fromDate`,
                   cd.thru_date      `categories.thruDate`,
                   s.id              `series.id`,
                   s.slug            `series.slug`,
                   s.name            `series.name`,
                   s.description     `series.description`,
                   r.id              `ratings.id`,
                   r.user_id         `ratings.user.id`,
                   r.rating          `ratings.rating`,
                   r.title           `ratings.title`,
                   r.content         `ratings.content`,
                   rr.id             `ratings.replies.id`,
                   rr.user_id        `ratings.replies.user.id`,
                   rr.content        `ratings.replies.content`
            FROM book b
                     LEFT JOIN book_image bi on b.id = bi.book_id
                     LEFT JOIN author_definition ad on b.id = ad.book_id
                     LEFT JOIN author a on ad.author_id = a.id
                     LEFT JOIN category_definition cd on b.id = cd.book_id
                     LEFT JOIN category c on cd.category_id = c.id
                     LEFT JOIN series s on b.series_id = s.id
                     LEFT JOIN rating r on b.id = r.book_id
                     LEFT JOIN reply rr on r.id = rr.rating_id
            WHERE isbn = :isbn
        ');

        $stmt->bindParam(':isbn', $this->isbn);
        return $stmt;
    }
}
