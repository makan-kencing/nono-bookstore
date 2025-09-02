<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Core\View;
use App\Exception\NotFoundException;
use App\Exception\Wrapper\WebExceptionWrapper;
use App\Repository\BookRepository;
use App\Repository\Query\BookCriteria;
use App\Repository\Query\BookQuery;
use App\Router\Method\GET;
use App\Router\Path;
use PDO;

#[Path('/book')]
readonly class ProductController extends WebController
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
        $query = BookQuery::withFullDetails()
            ->where(BookCriteria::byIsbn()
                ->and(BookCriteria::notSoftDeleted()))
            ->bind(':isbn', $isbn);

        $book = $this->bookRepository->getOne($query);
        if (!$book)
            throw new WebExceptionWrapper(new NotFoundException());

        if ($slug != $book->slug) {
            header('Location: ' . "/book/$isbn/$book->slug");
            return;
        }

        echo $this->render('webstore/book.php', [
            'book' => $book
        ]);
    }
}
