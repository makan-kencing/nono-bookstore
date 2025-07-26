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
            SELECT b.id                `book.id`,
                   b.slug              `book.slug`,
                   b.isbn              `book.isbn`,
                   b.title             `book.title`,
                   b.description       `book.description`,
                   b.publisher         `book.publisher`,
                   b.published_date    `book.publishedDate`,
                   b.number_of_pages   `book.numberOfPages`,
                   b.language          `book.language`,
                   b.dimensions        `book.dimensions`,
                   bi.id               `book.images.id`,
                   bi.image_url        `book.images.imageUrl`,
                   a.id                `book.authors.author.id`,
                   a.slug              `book.authors.author.slug`,
                   a.name              `book.authors.author.name`,
                   a.description       `book.authors.author.description`,
                   ad.type             `book.authors.type`,
                   ad.comment          `book.authors.comment`,
                   c.id                `book.categories.category.id`,
                   c.slug              `book.categories.category.slug`,
                   c.name              `book.categories.category.name`,
                   c.description       `book.categories.category.description`,
                   c.parent_id         `book.categories.category.parent.id`,
                   cd.is_primary       `book.categories.isPrimary`,
                   cd.comment          `book.categories.comment`,
                   cd.from_date        `book.categories.fromDate`,
                   cd.thru_date        `book.categories.thruDate`,
                   s.id                `book.series.id`,
                   s.slug              `book.series.slug`,
                   s.name              `book.series.name`,
                   s.description       `book.series.description`,
                   r.id                `book.ratings.id`,
                   r.rating            `book.ratings.rating`,
                   r.title             `book.ratings.title`,
                   r.content           `book.ratings.content`,
                   r.rated_at          `book.ratings.ratedAt`,
                   ru.id               `book.ratings.user.id`,
                   ru.username         `book.ratings.user.username`,
                   ru.email            `book.ratings.user.email`,
                   ru.hashed_password  `book.ratings.user.hashedPassword`,
                   ru.role             `book.ratings.user.role`,
                   ru.is_verified      `book.ratings.user.isVerified`,
                   rr.id               `book.ratings.replies.id`,
                   rr.content          `book.ratings.replies.content`,
                   rr.replied_at       `book.ratings.replies.repliedAt`,
                   rru.id              `book.ratings.replies.user.id`,
                   rru.username        `book.ratings.replies.user.username`,
                   rru.email           `book.ratings.replies.user.email`,
                   rru.hashed_password `book.ratings.replies.user.hashedPassword`,
                   rru.role            `book.ratings.replies.user.role`,
                   rru.is_verified     `book.ratings.replies.user.isVerified`
            FROM book b
                     LEFT JOIN book_image bi on b.id = bi.book_id
                     LEFT JOIN author_definition ad on b.id = ad.book_id
                     LEFT JOIN author a on ad.author_id = a.id
                     LEFT JOIN category_definition cd on b.id = cd.book_id
                     LEFT JOIN category c on cd.category_id = c.id
                     LEFT JOIN series s on b.series_id = s.id
                     LEFT JOIN rating r on b.id = r.book_id
                     LEFT JOIN user ru on r.user_id = ru.id
                     LEFT JOIN reply rr on r.id = rr.rating_id
                     LEFT JOIN user rru on rr.user_id = rru.id
            WHERE isbn = :isbn
        ');

        $stmt->bindParam(':isbn', $this->isbn);
        return $stmt;
    }
}
