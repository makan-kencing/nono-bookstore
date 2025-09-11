<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Core\View;
use App\DTO\Request\BookSearchDTO;
use App\Exception\BadRequestException;
use App\Exception\UnprocessableEntityException;
use App\Router\Method\GET;
use App\Router\Path;
use App\Service\BookService;
use PDO;

#[Path('/api/books')]
readonly class BooksController extends ApiController
{
    private BookService $bookService;

    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);
        $this->bookService = new BookService($pdo);
    }

    /**
     * @throws BadRequestException
     * @throws UnprocessableEntityException
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

        header('Content-Type: text/html');
        echo $this->render(
            'admin/book/_books_table.php',
            ['page' => $page, 'search' => $dto]
        );
    }
}
