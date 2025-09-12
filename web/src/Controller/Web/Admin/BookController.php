<?php

declare(strict_types=1);

namespace App\Controller\Web\Admin;

use App\Controller\Web\WebController;
use App\Core\View;
use App\Entity\User\UserRole;
use App\Exception\NotFoundException;
use App\Router\AuthRule;
use App\Router\Method\GET;
use App\Router\Path;
use App\Router\RequireAuth;
use App\Service\BookService;
use PDO;

#[Path('/admin/book')]
#[RequireAuth([UserRole::STAFF], rule: AuthRule::HIGHER_OR_EQUAL)]
readonly class BookController extends WebController
{
    private BookService $bookService;

    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);
        $this->bookService = new BookService($pdo);
    }

    /**
     * @throws NotFoundException
     */
    #[GET]
    #[Path('/{id}')]
    public function viewBook(string $id): void
    {
        $book = $this->bookService->getBookById((int) $id);
        if ($book === null)
            throw new NotFoundException();

        echo $this->render('admin/book/book.php', ['book' => $book]);
    }
}
