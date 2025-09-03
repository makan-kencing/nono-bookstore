<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Book\Book;
use App\Entity\Product\CoverType;
use App\Entity\Product\Product;
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
     * @param CoverType|null $type
     * @return ?array{0: Book, 1: Product}
     * @throws NotFoundException
     */
    public function getBookProductDetails(string $isbn, ?CoverType $type = null): ?array
    {
        $qb = BookQuery::withFullDetails()
            ->where(BookCriteria::byIsbn()
                ->and(BookCriteria::notSoftDeleted()))
            ->bind(':isbn', $isbn);

        $book = $this->bookRepository->getOne($qb);
        if ($book == null)
            return null;

        $product = array_find(
            $book->products,
            fn (Product $product) => $product->coverType == $type
        );
        if ($product == null)
            $product = current($book->products) ?: throw new NotFoundException();

        return [$book, $product];
    }
}
