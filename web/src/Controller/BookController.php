<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\View;
use App\Exception\NotFoundException;
use App\Exception\Wrapper\WebExceptionWrapper;
use App\Repository\BookRepository;
use App\Repository\Query\QueryBookWithFullDetail;
use PDO;

readonly class BookController extends Controller
{
    private BookRepository $bookRepository;

    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);
        $this->bookRepository = new BookRepository($pdo);
    }

    /**
     * @param array<string, string> $pathVars
     * @return void
     * @throws WebExceptionWrapper
     */
    public function viewBook(array $pathVars): void
    {
        $isbn = $pathVars['isbn'];
        $slug = $pathVars['slug'] ?? '';

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

    public function searchBook(): void
    {
    }
}
