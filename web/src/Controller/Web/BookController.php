<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Core\View;
use App\Exception\NotFoundException;
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
     * @return void
     * @throws NotFoundException
     */
    #[GET]
    #[Path('/{isbn}')]
    #[Path('/{isbn}/{slug}')]
    public function viewBook(string $isbn, string $slug = ''): void
    {
        $book = $this->bookService->getBookProductDetails($isbn) ?? throw new NotFoundException();

        if ($book->work->slug != $slug) {
            $this->redirect("/book/$isbn/{$book->work->slug}");
            return;
        }

        echo $this->render('webstore/book.php', [
            'book' => $book,
        ]);
    }
}
