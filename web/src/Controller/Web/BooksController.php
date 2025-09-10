<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Core\View;
use App\DTO\Request\BookSearchDTO;
use App\Exception\BadRequestException;
use App\Exception\NotFoundException;
use App\Exception\UnprocessableEntityException;
use App\Router\Method\GET;
use App\Router\Path;
use App\Service\BookService;
use PDO;

#[Path('/books')]
readonly class BooksController extends WebController
{
    private BookService $bookService;

    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);
        $this->bookService = new BookService($pdo);
    }

    /**
     * @throws UnprocessableEntityException
     * @throws BadRequestException
     */
    #[GET]
    #[Path('/search')]
    #[Path('/search/')]
    #[Path('/search/{query}')]
    public function search(?string $query = null): void
    {
        if ($query !== null)
            $_GET['query'] = $query;

        $dto = BookSearchDTO::jsonDeserialize($_GET);
        $dto->validate();

        $page = $this->bookService->search($dto);
        if ($page->total == 1) {
            $book = $page->items[0];

            http_response_code(303);
            $this->redirect('/book/' . $book->isbn . '/' . $book->work->slug);
            return;
        }

        echo $this->render(
            'webstore/books/search.php',
            ['page' => $page, 'search' => $dto]
        );
    }
}
