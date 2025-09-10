<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\Request\BookSearchDTO;
use App\DTO\Request\BookSearchSortOption;
use App\DTO\Response\PageResultDTO;
use App\DTO\Response\WorkRating\RatingDTO;
use App\DTO\Response\WorkRating\RatingSummaryDTO;
use App\Entity\Book\Book;
use App\Entity\Book\Work;
use App\Orm\QueryBuilder;
use App\Repository\BookRepository;
use App\Repository\Query\AuthorCriteria;
use App\Repository\Query\BookCriteria;
use App\Repository\Query\BookQuery;
use App\Repository\Query\CategoryCriteria;
use App\Repository\Query\PriceCriteria;
use App\Repository\Query\RatingCriteria;
use App\Repository\Query\RatingQuery;
use App\Repository\Query\WorkCriteria;
use App\Repository\RatingRepository;
use PDO;

readonly class BookService extends Service
{
    private BookRepository $bookRepository;
    private RatingRepository $ratingRepository;

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        $this->bookRepository = new BookRepository($this->pdo);
        $this->ratingRepository = new RatingRepository($this->pdo);
    }

    /**
     * @param string $isbn
     * @return ?Book
     */
    public function getBookProductDetails(string $isbn): ?Book
    {
        $qb = BookQuery::asBookDetails();
        $qb->where(BookCriteria::byIsbn(alias: 'b')
                ->and(BookCriteria::notSoftDeleted(alias: 'b')))
            ->bind(':isbn', $isbn);

        $book = $this->bookRepository->getOne($qb);
        if ($book == null)
            return null;

        return $book;
    }

    /**
     * @param int|Work $work
     * @return RatingDTO[]
     */
    public function getRatings(int|Work $work): array
    {
        if ($work instanceof Work) $work = $work->id;

        $qb = RatingQuery::withFullDetails();
        $qb->where(RatingCriteria::byWork())
            ->bind(':work_id', $work);

        return array_map(
            [RatingDTO::class, 'fromRating'],
            $this->ratingRepository->get($qb)
        );
    }

    public function getRatingSummary(int|Work $work): RatingSummaryDTO
    {
        if ($work instanceof Work) $work = $work->id;

        return $this->ratingRepository->getRatingSummary($work);
    }

    /**
     * @param BookSearchDTO $dto
     * @return PageResultDTO<Book>
     */
    public function search(BookSearchDTO $dto): PageResultDTO
    {
        $qb = BookQuery::asBookListing();

        $predicates = BookCriteria::notSoftDeleted(alias: 'b');

        // is query string an isbn string?
        if ($dto->isIsbnQuery()) {
            $predicates = $predicates->and(BookCriteria::byIsbn(alias: 'b'));
            $qb->bind(':isbn', $dto->query);
        }

        if ($dto->query !== null) {
            $predicates = $predicates->and(WorkCriteria::byTitleLike(alias: 'w'));
            $qb->bind(':title', '%' . $dto->query . '%');
        }

        if ($dto->categoryId !== null) {
            $predicates = $predicates->and(CategoryCriteria::byId(':category_id', alias: 'c'));
            $qb->bind(':category_id', $dto->categoryId);
        }

        if ($dto->minPrice !== null && $dto->maxPrice !== null) {
            $predicates = $predicates->and(PriceCriteria::byAmountBetween(alias: 'p'));
            $qb->bind(':low', $dto->minPrice);
            $qb->bind(':high', $dto->maxPrice);
        }

        if ($dto->authorId !== null) {
            $predicates = $predicates->and(AuthorCriteria::byId('author_id', alias: 'ba'));
            $qb->bind(':author_id', $dto->authorId);
        }

        if ($dto->publisher !== null) {
            $predicates = $predicates->and(BookCriteria::byPublisher(alias: 'b'));
            $qb->bind(':publisher', $dto->publisher);
        }

        if ($dto->language !== null) {
            $predicates = $predicates->and(BookCriteria::byLanguage(alias: 'b'));
            $qb->bind(':language', $dto->language);
        }

        if ($dto->option) {
            $direction = $dto->option->getDirection();

            $property = match ($this) {
                BookSearchSortOption::RELEVANCE => 'b.id',
                BookSearchSortOption::PRICE_ASC,
                BookSearchSortOption::PRICE_DESC => 'p.amount',
                BookSearchSortOption::TITLE_ASC,
                BookSearchSortOption::TITLE_DESC => 'w.title',
                BookSearchSortOption::PUBLISHED_ASC,
                BookSearchSortOption::PUBLISHED_DESC => 'b.published_at',
            };

            $qb->orderBy($property, $direction);
        }

        $qb->where($predicates);
        $qb->page($dto->toPageRequest());

        $this->pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
        try {
            return new PageResultDTO(
                $this->bookRepository->get($qb),
                $this->bookRepository->count($qb),
                $dto->toPageRequest()
            );
        } finally {
            $this->pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
        }
    }
}
