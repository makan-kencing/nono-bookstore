<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Core\View;
use App\Exception\NotFoundException;
use App\Exception\Wrapper\WebExceptionWrapper;
use App\Repository\BookRepository;
use App\Repository\Query\QueryBookWithFullDetail;
use App\Router\Method\GET;
use App\Router\Path;
use PDO;

#[Path('/book')]
readonly class BookController extends WebController
{
    private BookRepository $bookRepository;

    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);
        $this->bookRepository = new BookRepository($pdo);
    }

    /**
     * View a book and its details.
     *
     * @param string $isbn The isbn of the book
     * @param string $slug The url slug of the book. Used for readability purposes.
     * @param string $type The cover type of the book. Defaults to Paperback.
     * @return void
     * @throws WebExceptionWrapper
     */
    #[GET]
    #[Path('/{isbn}')]
    #[Path('/{isbn}/{slug}')]
    #[Path('/{isbn}/{slug}/{type}')]
    public function viewBook(string $isbn, string $slug = '', string $type = '1'): void
    {
        $query = new QueryBookWithFullDetail();
        $query->isbn = $isbn;

        $book = $this->bookRepository->get($query);
        if (!$book) {
            throw new WebExceptionWrapper(new NotFoundException());
        }
        $book = $book[0];

        if ($slug != $book->slug) {
            header('Location: ' . "/book/$isbn/$book->slug");
            return;
        }

        echo $this->render('webstore/book.php', [
            'book' => $book
        ]);
    }
}
