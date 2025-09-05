<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Book\Work;
use App\Entity\Book\Book;
use App\Entity\Product\CoverType;
use App\Exception\NotFoundException;
use App\Repository\BookRepository;
use App\Repository\Query\BookCriteria;
use App\Repository\Query\BookQuery;
use PDO;

readonly class BookService extends Service
{
    private BookRepository $bookRepository;

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        $this->bookRepository = new BookRepository($this->pdo);
    }

    /**
     * @param string $isbn
     * @return ?Book
     * @throws NotFoundException
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
}
