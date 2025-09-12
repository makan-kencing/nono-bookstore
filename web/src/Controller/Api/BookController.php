<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Core\View;
use App\DTO\Request\BookCreate\BookCreateDTO;
use App\Entity\User\UserRole;
use App\Exception\BadRequestException;
use App\Exception\ConflictException;
use App\Exception\UnprocessableEntityException;
use App\Router\AuthRule;
use App\Router\Method\DELETE;
use App\Router\Method\POST;
use App\Router\Method\PUT;
use App\Router\Path;
use App\Router\RequireAuth;
use App\Service\BookService;
use PDO;

#[Path('/api/book')]
readonly class BookController extends ApiController
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
     * @throws ConflictException
     */
    #[POST]
    #[RequireAuth([UserRole::STAFF], rule: AuthRule::HIGHER_OR_EQUAL, redirect: false)]
    public function createBook(): void
    {
        $dto = BookCreateDTO::jsonDeserialize($_POST);
        $dto->validate();

        $this->bookService->createBook($dto);

        http_response_code(201);
    }

    #[PUT]
    #[RequireAuth([UserRole::STAFF], rule: AuthRule::HIGHER_OR_EQUAL, redirect: false)]
    public function updateBook(string $id): void
    {

    }

    #[DELETE]
    #[RequireAuth([UserRole::STAFF], rule: AuthRule::HIGHER_OR_EQUAL, redirect: false)]
    public function deleteBook(string $id): void
    {

    }
}
