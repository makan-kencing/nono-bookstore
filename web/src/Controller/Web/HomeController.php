<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Core\View;
use App\DTO\Request\BookSearchDTO;
use App\DTO\Request\BookSearchSortOption;
use App\Router\Method\GET;
use App\Router\Path;
use App\Service\BookService;
use PDO;

#[Path('/')]
readonly class HomeController extends WebController
{
    private BookService $bookService;

    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);
        $this->bookService = new BookService($pdo);
    }

    #[GET]
    public function index(): void
    {
        $newest = $this->bookService->search(new BookSearchDTO(
            option: BookSearchSortOption::PUBLISHED_DESC,
            pageSize: 10
        ));
        $top = $this->bookService->search(new BookSearchDTO(
            option: BookSearchSortOption::RELEVANCE,
            pageSize: 10
        ));
        $books = $this->bookService->search(new BookSearchDTO(
            option: BookSearchSortOption::RELEVANCE,
            pageSize: 25
        ));


        echo $this->render(
            'webstore/home.php',
            ['newest' => $newest, 'top' => $top,'books'=>$books]
        );
    }
}
