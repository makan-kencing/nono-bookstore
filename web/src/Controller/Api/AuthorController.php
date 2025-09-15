<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Core\View;
use App\DTO\Request\SearchDTO;
use App\Entity\Book\Author\Author;
use App\Entity\User\UserRole;
use App\Exception\ConflictException;
use App\Router\AuthRule;
use App\Router\Method\GET;
use App\Router\Method\POST;
use App\Router\Path;
use App\Router\RequireAuth;
use App\Service\BookService;
use PDO;

#[Path('/api/author')]
readonly class AuthorController extends ApiController
{
    private BookService $bookService;

    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);
        $this->bookService = new BookService($pdo);
    }

    #[GET]
    #[Path('/options/{query}')]
    public function getOptions(string $query): void
    {
        $dto = new SearchDTO($query);

        $page = $this->bookService->searchAuthor($dto);

        header('Content-Type: text/html');
        foreach ($page->items as $item)
            echo "<option value='$item->id'>$item->name</option>";
    }

    #[POST]
    #[Path('/name/{name}')]
    #[RequireAuth([UserRole::STAFF], rule: AuthRule::HIGHER_OR_EQUAL, redirect: false)]
    public function createFromName(string $name): void
    {
        $author = $this->bookService->createAuthorFromName($name);

        header('Content-Type: application/json');
        echo json_encode([
            'id' => $author->id,
            'slug' => $author->slug,
            'name' => $author->name
        ]);
    }
}
