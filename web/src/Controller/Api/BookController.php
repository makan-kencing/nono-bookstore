<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\DTO\Request\BookCreate\BookCreateDTO;
use App\Entity\User\UserRole;
use App\Router\AuthRule;
use App\Router\Method\DELETE;
use App\Router\Method\POST;
use App\Router\Method\PUT;
use App\Router\Path;
use App\Router\RequireAuth;

#[Path('/api/book')]
readonly class BookController extends ApiController
{
    #[POST]
    #[RequireAuth([UserRole::STAFF], rule: AuthRule::HIGHER_OR_EQUAL, redirect: false)]
    public function createBook(): void
    {
        $dto = BookCreateDTO::jsonDeserialize($_POST);
        $dto->validate();

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
