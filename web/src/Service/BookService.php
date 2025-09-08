<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\Response\WorkRating\RatingDTO;
use App\DTO\Response\WorkRating\RatingSummaryDTO;
use App\Entity\Book\Book;
use App\Entity\Book\Work;
use App\Repository\BookRepository;
use App\Repository\Query\BookCriteria;
use App\Repository\Query\BookQuery;
use App\Repository\Query\RatingCriteria;
use App\Repository\Query\RatingQuery;
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
        $qb = BookQuery::asBookListing()
            ->where(BookCriteria::byIsbn()
                ->and(BookCriteria::notSoftDeleted()))
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

        $qb = RatingQuery::withFullDetails()
            ->where(RatingCriteria::byWork())
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
}
