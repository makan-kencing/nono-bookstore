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
     * @param string $isbn
     * @param string $slug
     * @return void
     * @throws WebExceptionWrapper
     */
    #[Path('/{isbn}/{slug}')]
    #[GET]
    public function viewBook(string $isbn, string $slug): void
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

        echo $this->render('book.php', [
            'book' => $book
        ]);
    }
}
