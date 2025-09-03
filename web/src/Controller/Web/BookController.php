<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Core\View;
use App\Entity\Book\Book;
use App\Entity\Product\CoverType;
use App\Entity\Product\Product;
use App\Exception\NotFoundException;
use App\Repository\Query\BookCriteria;
use App\Repository\Query\BookQuery;
use App\Router\Method\GET;
use App\Router\Path;
use App\Service\BookService;
use PDO;

#[Path('/book')]
readonly class BookController extends WebController
{
    private BookService $bookService;

    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);
        $this->bookService = new BookService($pdo);
    }

    /**
     * View a book and its details.
     *
     * @param string $isbn The isbn of the book
     * @param string $slug The url slug of the book. Used for readability purposes.
     * @param string $type The cover type of the book. Defaults to Paperback.
     * @return void
     * @throws NotFoundException
     */
    #[GET]
    #[Path('/{isbn}')]
    #[Path('/{isbn}/{slug}')]
    #[Path('/{isbn}/{slug}/{type}')]
    public function viewBook(string $isbn, string $slug = '', string $type = '1'): void
    {
        $type = CoverType::tryFrom((int) $type);

        /**
         * @var Book $book
         * @var Product $product
         */
        list($book, $product) = $this->bookService->getBookProductDetails($isbn, $type) ?? throw new NotFoundException();

        if ($book->slug != $slug) {
            header('Location: ' . "/book/$isbn/$book->slug");
            return;
        }

        if ($product->coverType != $type) {
            header('Location: ' . "/book/$isbn/$book->slug/{$product->coverType->value}");
            return;
        }

        echo $this->render('webstore/book.php', [
            'book' => $book,
            'selectedProduct' => $product
        ]);
    }
}
