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
     * @param array<string, string> $vars
     * @return void
     * @throws WebExceptionWrapper
     */
    public function viewBook(array $vars): void
    {
        $isbn = $vars['isbn'];
        $slug = $vars['slug'] ?? '';

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

    /**
     * @param array<string, string> $vars
     * @return void
     */
    public function searchBook(array $vars): void
    {
    }
}
