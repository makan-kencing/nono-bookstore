<?php

declare(strict_types=1);

namespace App\Repository\Query;

use App\Entity\Book\Book;
use App\Repository\Mapper\AuthorDefinitionRowMapper;
use App\Repository\Mapper\AuthorRowMapper;
use App\Repository\Mapper\BookImageRowMapper;
use App\Repository\Mapper\BookRowMapper;
use App\Repository\Mapper\CategoryDefinitionRowMapper;
use App\Repository\Mapper\CategoryRowMapper;
use App\Repository\Mapper\FileRowMapper;
use App\Repository\Mapper\PublisherRowMapper;
use App\Repository\Mapper\RatingRowMapper;
use App\Repository\Mapper\ReplyRowMapper;
use App\Repository\Mapper\SeriesDefinitionRowMapper;
use App\Repository\Mapper\SeriesRowMapper;
use App\Repository\Mapper\UserRowMapper;
use PDO;
use PDOStatement;

/**
 * @extends Query<Book>
 */
class QueryBookWithFullDetail extends Query
{
    public string $isbn;

    public function createQuery(PDO $pdo, string $prefix = ''): PDOStatement
    {
        $c = function (string $s) {
            return $s;
        };

        // phpcs:disable
        $stmt = $pdo->prepare("
            SELECT b.id                `$prefix{$c(BookRowMapper::ID)}`,
                   b.slug              `$prefix{$c(BookRowMapper::SLUG)}`,
                   b.isbn              `$prefix{$c(BookRowMapper::ISBN)}`,
                   b.title             `$prefix{$c(BookRowMapper::TITLE)}`,
                   b.description       `$prefix{$c(BookRowMapper::DESCRIPTION)}`,
                   p.id                `$prefix{$c(BookRowMapper::PUBLISHER)}{$c(PublisherRowMapper::ID)}`,
                   p.slug              `$prefix{$c(BookRowMapper::PUBLISHER)}{$c(PublisherRowMapper::SLUG)}`,
                   p.name              `$prefix{$c(BookRowMapper::PUBLISHER)}{$c(PublisherRowMapper::NAME)}`,
                   b.published_date    `$prefix{$c(BookRowMapper::PUBLISHED_DATE)}`,
                   b.number_of_pages   `$prefix{$c(BookRowMapper::NUMBER_OF_PAGES)}`,
                   b.language          `$prefix{$c(BookRowMapper::LANGUAGE)}`,
                   b.dimensions        `$prefix{$c(BookRowMapper::DIMENSION)}`,
                   bi.image_order      `$prefix{$c(BookRowMapper::IMAGES)}{$c(BookImageRowMapper::IMAGE_ORDER)}`,
                   f.id                `$prefix{$c(BookRowMapper::IMAGES)}{$c(BookImageRowMapper::FILE)}{$c(FileRowMapper::ID)}`,
                   f.filename          `$prefix{$c(BookRowMapper::IMAGES)}{$c(BookImageRowMapper::FILE)}{$c(FileRowMapper::FILENAME)}`,
                   f.mimetype          `$prefix{$c(BookRowMapper::IMAGES)}{$c(BookImageRowMapper::FILE)}{$c(FileRowMapper::MIMETYPE)}`,
                   f.alt               `$prefix{$c(BookRowMapper::IMAGES)}{$c(BookImageRowMapper::FILE)}{$c(FileRowMapper::ALT)}`,
                   f.filepath          `$prefix{$c(BookRowMapper::IMAGES)}{$c(BookImageRowMapper::FILE)}{$c(FileRowMapper::FILEPATH)}`,
                   f.created_at        `$prefix{$c(BookRowMapper::IMAGES)}{$c(BookImageRowMapper::FILE)}{$c(FileRowMapper::CREATED_AT)}`,
                   f.created_by        `$prefix{$c(BookRowMapper::IMAGES)}{$c(BookImageRowMapper::FILE)}{$c(FileRowMapper::CREATED_BY)}{$c(UserRowMapper::ID)}`,
                   a.id                `$prefix{$c(BookRowMapper::AUTHORS)}{$c(AuthorDefinitionRowMapper::AUTHOR)}{$c(AuthorRowMapper::ID)}`,
                   a.slug              `$prefix{$c(BookRowMapper::AUTHORS)}{$c(AuthorDefinitionRowMapper::AUTHOR)}{$c(AuthorRowMapper::SLUG)}`,
                   a.name              `$prefix{$c(BookRowMapper::AUTHORS)}{$c(AuthorDefinitionRowMapper::AUTHOR)}{$c(AuthorRowMapper::NAME)}`,
                   a.description       `$prefix{$c(BookRowMapper::AUTHORS)}{$c(AuthorDefinitionRowMapper::AUTHOR)}{$c(AuthorRowMapper::DESCRIPTION)}`,
                   ad.type             `$prefix{$c(BookRowMapper::AUTHORS)}{$c(AuthorDefinitionRowMapper::TYPE)}`,
                   ad.comment          `$prefix{$c(BookRowMapper::AUTHORS)}{$c(AuthorDefinitionRowMapper::COMMENT)}`,
                   c.id                `$prefix{$c(BookRowMapper::CATEGORIES)}{$c(CategoryDefinitionRowMapper::CATEGORY)}{$c(CategoryRowMapper::ID)}`,
                   c.slug              `$prefix{$c(BookRowMapper::CATEGORIES)}{$c(CategoryDefinitionRowMapper::CATEGORY)}{$c(CategoryRowMapper::SLUG)}`,
                   c.name              `$prefix{$c(BookRowMapper::CATEGORIES)}{$c(CategoryDefinitionRowMapper::CATEGORY)}{$c(CategoryRowMapper::NAME)}`,
                   c.description       `$prefix{$c(BookRowMapper::CATEGORIES)}{$c(CategoryDefinitionRowMapper::CATEGORY)}{$c(CategoryRowMapper::DESCRIPTION)}`,
                   c.parent_id         `$prefix{$c(BookRowMapper::CATEGORIES)}{$c(CategoryDefinitionRowMapper::CATEGORY)}{$c(CategoryRowMapper::PARENT)}{$c(CategoryRowMapper::ID)}`,
                   cd.is_primary       `$prefix{$c(BookRowMapper::CATEGORIES)}{$c(CategoryDefinitionRowMapper::IS_PRIMARY)}`,
                   cd.comment          `$prefix{$c(BookRowMapper::CATEGORIES)}{$c(CategoryDefinitionRowMapper::COMMENT)}`,
                   cd.from_date        `$prefix{$c(BookRowMapper::CATEGORIES)}{$c(CategoryDefinitionRowMapper::FROM_DATE)}`,
                   cd.thru_date        `$prefix{$c(BookRowMapper::CATEGORIES)}{$c(CategoryDefinitionRowMapper::THRU_DATE)}`,
                   s.id                `$prefix{$c(BookRowMapper::SERIES)}{$c(SeriesDefinitionRowMapper::SERIES)}{$c(SeriesRowMapper::ID)}`,
                   s.slug              `$prefix{$c(BookRowMapper::SERIES)}{$c(SeriesDefinitionRowMapper::SERIES)}{$c(SeriesRowMapper::SLUG)}`,
                   s.name              `$prefix{$c(BookRowMapper::SERIES)}{$c(SeriesDefinitionRowMapper::SERIES)}{$c(SeriesRowMapper::NAME)}`,
                   s.description       `$prefix{$c(BookRowMapper::SERIES)}{$c(SeriesDefinitionRowMapper::SERIES)}{$c(SeriesRowMapper::DESCRIPTION)}`,
                   sd.position         `$prefix{$c(BookRowMapper::SERIES)}{$c(SeriesDefinitionRowMapper::POSITION)}`,
                   sd.series_order     `$prefix{$c(BookRowMapper::SERIES)}{$c(SeriesDefinitionRowMapper::SERIES_ORDER)}`,
                   r.id                `$prefix{$c(BookRowMapper::RATINGS)}{$c(RatingRowMapper::ID)}`,
                   r.rating            `$prefix{$c(BookRowMapper::RATINGS)}{$c(RatingRowMapper::RATING)}`,
                   r.title             `$prefix{$c(BookRowMapper::RATINGS)}{$c(RatingRowMapper::TITLE)}`,
                   r.content           `$prefix{$c(BookRowMapper::RATINGS)}{$c(RatingRowMapper::CONTENT)}`,
                   r.rated_at          `$prefix{$c(BookRowMapper::RATINGS)}{$c(RatingRowMapper::RATED_AT)}`,
                   ru.id               `$prefix{$c(BookRowMapper::RATINGS)}{$c(RatingRowMapper::USER)}{$c(UserRowMapper::ID)}`,
                   ru.username         `$prefix{$c(BookRowMapper::RATINGS)}{$c(RatingRowMapper::USER)}{$c(UserRowMapper::USERNAME)}`,
                   ru.email            `$prefix{$c(BookRowMapper::RATINGS)}{$c(RatingRowMapper::USER)}{$c(UserRowMapper::EMAIL)}`,
                   ru.hashed_password  `$prefix{$c(BookRowMapper::RATINGS)}{$c(RatingRowMapper::USER)}{$c(UserRowMapper::HASHED_PASSWORD)}`,
                   ru.role             `$prefix{$c(BookRowMapper::RATINGS)}{$c(RatingRowMapper::USER)}{$c(UserRowMapper::ROLE)}`,
                   ru.is_verified      `$prefix{$c(BookRowMapper::RATINGS)}{$c(RatingRowMapper::USER)}{$c(UserRowMapper::IS_VERIFIED)}`,
                   rr.id               `$prefix{$c(BookRowMapper::RATINGS)}{$c(RatingRowMapper::REPLIES)}{$c(ReplyRowMapper::ID)}`,
                   rr.content          `$prefix{$c(BookRowMapper::RATINGS)}{$c(RatingRowMapper::REPLIES)}{$c(ReplyRowMapper::CONTENT)}`,
                   rr.replied_at       `$prefix{$c(BookRowMapper::RATINGS)}{$c(RatingRowMapper::REPLIES)}{$c(ReplyRowMapper::REPLIED_AT)}`,
                   rru.id              `$prefix{$c(BookRowMapper::RATINGS)}{$c(RatingRowMapper::REPLIES)}{$c(ReplyRowMapper::USER)}{$c(UserRowMapper::ID)}`,
                   rru.username        `$prefix{$c(BookRowMapper::RATINGS)}{$c(RatingRowMapper::REPLIES)}{$c(ReplyRowMapper::USER)}{$c(UserRowMapper::USERNAME)}`,
                   rru.email           `$prefix{$c(BookRowMapper::RATINGS)}{$c(RatingRowMapper::REPLIES)}{$c(ReplyRowMapper::USER)}{$c(UserRowMapper::EMAIL)}`,
                   rru.hashed_password `$prefix{$c(BookRowMapper::RATINGS)}{$c(RatingRowMapper::REPLIES)}{$c(ReplyRowMapper::USER)}{$c(UserRowMapper::HASHED_PASSWORD)}`,
                   rru.role            `$prefix{$c(BookRowMapper::RATINGS)}{$c(RatingRowMapper::REPLIES)}{$c(ReplyRowMapper::USER)}{$c(UserRowMapper::ROLE)}`,
                   rru.is_verified     `$prefix{$c(BookRowMapper::RATINGS)}{$c(RatingRowMapper::REPLIES)}{$c(ReplyRowMapper::USER)}{$c(UserRowMapper::IS_VERIFIED)}`
            FROM book b
                     JOIN publisher p on b.publisher_id = p.id
                     LEFT JOIN book_image bi on b.id = bi.book_id
                     LEFT JOIN file f on bi.file_id = f.id
                     JOIN author_definition ad on b.id = ad.book_id
                     JOIN author a on ad.author_id = a.id
                     LEFT JOIN category_definition cd on b.id = cd.book_id
                     LEFT JOIN category c on cd.category_id = c.id
                     LEFT JOIN series_definition sd on b.id = sd.book_id
                     LEFT JOIN series s on sd.series_id = s.id
                     LEFT JOIN rating r on b.id = r.book_id
                     LEFT JOIN user ru on r.user_id = ru.id
                     LEFT JOIN reply rr on r.id = rr.rating_id
                     LEFT JOIN user rru on rr.user_id = rru.id
            WHERE isbn = :isbn
        ");
        // phpcs:enable

        $stmt->bindValue(':isbn', $this->isbn);
        return $stmt;
    }
}
