<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Core\View;
use App\DTO\Request\BookCreate\BookCreateDTO;
use App\DTO\Request\BookCreate\BookUpdateDTO;
use App\DTO\Request\BookSearchDTO;
use App\Entity\Book\Author\AuthorDefinitionType;
use App\Entity\Product\InventoryLocation;
use App\Entity\User\UserRole;
use App\Exception\BadRequestException;
use App\Exception\ConflictException;
use App\Exception\ContentTooLargeException;
use App\Exception\NotFoundException;
use App\Exception\UnauthorizedException;
use App\Exception\UnprocessableEntityException;
use App\Router\AuthRule;
use App\Router\Method\DELETE;
use App\Router\Method\GET;
use App\Router\Method\POST;
use App\Router\Method\PUT;
use App\Router\Path;
use App\Router\RequireAuth;
use App\Service\BookService;
use PDO;
use Throwable;

#[Path('/api/book')]
readonly class BookController extends ApiController
{
    private BookService $bookService;

    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);
        $this->bookService = new BookService($pdo);
    }

    #[GET]
    #[Path('/isbn/{isbn}')]
    public function checkIsbnExists(string $isbn): void
    {
        header('Content-Type: application/json');
        echo json_encode(['exists' => $this->bookService->checkIsbnExists($isbn)]);
    }

    /**
     * @throws UnprocessableEntityException
     * @throws BadRequestException
     * @throws ConflictException
     * @throws Throwable
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

    /**
     * @throws NotFoundException
     * @throws ConflictException
     * @throws BadRequestException
     * @throws UnprocessableEntityException
     */
    #[PUT]
    #[Path('/{id}')]
    #[RequireAuth([UserRole::STAFF], rule: AuthRule::HIGHER_OR_EQUAL, redirect: false)]
    public function editBook(string $id): void
    {
        $dto = BookUpdateDTO::jsonDeserialize(array_merge([$id], self::getJsonBody()));
        $dto->validate();

        $this->bookService->updateBook($dto);

        http_response_code(201);
    }

    #[DELETE]
    #[Path('/{id}')]
    #[RequireAuth([UserRole::STAFF], rule: AuthRule::HIGHER_OR_EQUAL, redirect: false)]
    public function deleteBook(string $id): void
    {
        try {
            $this->bookService->deleteBook((int)$id);
        } catch (ConflictException) {
            $this->bookService->softDeleteBook((int)$id);
        }

        http_response_code(204);
    }

    /**
     * @throws BadRequestException
     */
    #[POST]
    #[Path('/{bookId}/author')]
    #[RequireAuth([UserRole::STAFF], rule: AuthRule::HIGHER_OR_EQUAL, redirect: false)]
    public function addAuthor(string $bookId): void
    {
        $json = self::getJsonBody();

        $authorId = (int) ($json['author_id'] ?? throw new BadRequestException());
        $type = AuthorDefinitionType::fromName($json['type'] ?? throw new BadRequestException());

        $this->bookService->addAuthor((int)$bookId, (int)$authorId, $type);
    }

    #[DELETE]
    #[Path('/{bookId}/author/{authorId}')]
    #[RequireAuth([UserRole::STAFF], rule: AuthRule::HIGHER_OR_EQUAL, redirect: false)]
    public function deleteAuthor(string $bookId, string $authorId): void
    {
        $this->bookService->removeAuthor((int)$bookId, (int)$authorId);
    }

    /**
     * @throws BadRequestException
     */
    #[POST]
    #[Path('/{bookId}/stock')]
    #[RequireAuth([UserRole::STAFF], rule: AuthRule::HIGHER_OR_EQUAL, redirect: false)]
    public function addInventory(string $bookId): void
    {
        $json = self::getJsonBody();

        $location = InventoryLocation::tryFromName($json['location'] ?? null) ?? throw new BadRequestException();
        $quantity = (int)($json['quantity'] ?? throw new BadRequestException());

        $this->bookService->insertInventory((int)$bookId, $location, $quantity);
    }

    /**
     * @throws BadRequestException
     */
    #[PUT]
    #[Path('/{bookId}/stock/{inventoryId}')]
    #[RequireAuth([UserRole::STAFF], rule: AuthRule::HIGHER_OR_EQUAL, redirect: false)]
    public function editStock(string $bookId, string $inventoryId): void
    {
        $json = self::getJsonBody();

        $quantity = (int)($json['quantity'] ?? throw new BadRequestException());

        $this->bookService->updateInventory((int)$inventoryId, $quantity);
    }

    /**
     * @throws BadRequestException
     */
    #[POST]
    #[Path('/{bookId}/price')]
    #[RequireAuth([UserRole::STAFF], rule: AuthRule::HIGHER_OR_EQUAL, redirect: false)]
    public function setPrice(string $bookId): void
    {
        $json = self::getJsonBody();

        $amount = $json['amount'] ?? throw new BadRequestException();
        $amount = (float) $amount * 100;
        $amount = (int) $amount;

        $this->bookService->setNewPrice((int)$bookId, $amount);
    }

    /**
     * @throws BadRequestException
     */
    #[POST]
    #[Path('/{bookId}/cost')]
    #[RequireAuth([UserRole::STAFF], rule: AuthRule::HIGHER_OR_EQUAL, redirect: false)]
    public function setCost(string $bookId): void
    {
        $json = self::getJsonBody();

        $amount = $json['amount'] ?? throw new BadRequestException();
        $amount = (float) $amount * 100;
        $amount = (int) $amount;

        $this->bookService->setNewCost((int)$bookId, $amount);
    }

    /**
     * @throws UnauthorizedException
     * @throws NotFoundException
     * @throws ConflictException
     * @throws BadRequestException
     * @throws UnprocessableEntityException
     * @throws ContentTooLargeException
     */
    #[POST]
    #[Path('/{bookId}/image')]
    #[RequireAuth([UserRole::STAFF], rule: AuthRule::HIGHER_OR_EQUAL, redirect: false)]
    public function uploadImages(string $bookId): void
    {
        $files = $this->normalizeFiles($_FILES["images"]);

        $this->bookService->uploadImage((int) $bookId, ...$files);
    }

    #[DELETE]
    #[Path('/{bookId}/image/{fileId}')]
    #[RequireAuth([UserRole::STAFF], rule: AuthRule::HIGHER_OR_EQUAL, redirect: false)]
    public function removeImage(string $bookId, string $fileId): void
    {
        $this->bookService->removeImage((int) $bookId, (int) $fileId);
    }

    #[PUT]
    #[Path('/{bookId}/image/{fileId}')]
    #[RequireAuth([UserRole::STAFF], rule: AuthRule::HIGHER_OR_EQUAL, redirect: false)]
    public function updateImageOrder(string $bookId, string $fileId): void
    {
        $json = self::getJsonBody();

        $this->bookService->moveImage((int) $bookId, (int) $fileId, (int) $json['image_order']);
    }

    /**
     * @throws BadRequestException
     * @throws UnprocessableEntityException
     */
    #[GET]
    #[Path('/search/')]
    #[Path('/search/{query}')]
    #[RequireAuth([UserRole::STAFF], rule: AuthRule::HIGHER_OR_EQUAL, redirect: false)]
    public function search(?string $query = null): void
    {
        if ($query !== null)
            $_GET['query'] = $query;

        $dto = BookSearchDTO::jsonDeserialize($_GET);
        $dto->validate();

        $page = $this->bookService->search($dto);

        if ($_SERVER['HTTP_ACCEPT'] === 'text/html') {
            header('Content-Type: text/html');
            echo $this->render(
                'admin/book/_books_table.php',
                ['page' => $page, 'search' => $dto]
            );
        }
    }
}
