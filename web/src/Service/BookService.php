<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\Request\BookCreate\BookCreateDTO;
use App\DTO\Request\BookCreate\BookUpdateDTO;
use App\DTO\Request\BookSearchDTO;
use App\DTO\Request\BookSearchSortOption;
use App\DTO\Request\SearchDTO;
use App\DTO\Response\PageResultDTO;
use App\DTO\Response\WorkRating\RatingDTO;
use App\DTO\Response\WorkRating\RatingSummaryDTO;
use App\Entity\Book\Author\Author;
use App\Entity\Book\Author\AuthorDefinition;
use App\Entity\Book\Author\AuthorDefinitionType;
use App\Entity\Book\Book;
use App\Entity\Book\BookImage;
use App\Entity\Book\Category\Category;
use App\Entity\Book\Work;
use App\Entity\File;
use App\Entity\Product\Cost;
use App\Entity\Product\Inventory;
use App\Entity\Product\InventoryLocation;
use App\Entity\Product\Price;
use App\Exception\BadRequestException;
use App\Exception\ConflictException;
use App\Exception\ContentTooLargeException;
use App\Exception\NotFoundException;
use App\Exception\UnauthorizedException;
use App\Exception\UnprocessableEntityException;
use App\Orm\QueryBuilder;
use App\Repository\BookRepository;
use App\Repository\Query\AuthorCriteria;
use App\Repository\Query\AuthorQuery;
use App\Repository\Query\BookCriteria;
use App\Repository\Query\BookQuery;
use App\Repository\Query\CategoryCriteria;
use App\Repository\Query\CategoryQuery;
use App\Repository\Query\PriceCriteria;
use App\Repository\Query\RatingCriteria;
use App\Repository\Query\RatingQuery;
use App\Repository\Query\WorkCriteria;
use App\Repository\Query\WorkQuery;
use App\Repository\RatingRepository;
use DateTime;
use PDO;
use PDOException;
use Throwable;
use function App\Utils\array_find_key;
use function App\Utils\array_move_elem;

/**
 * @phpstan-import-type PhpFile from FileService
 */
readonly class BookService extends Service
{
    private BookRepository $bookRepository;
    private RatingRepository $ratingRepository;
    private FileService $fileService;

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        $this->bookRepository = new BookRepository($this->pdo);
        $this->ratingRepository = new RatingRepository($this->pdo);
        $this->fileService = new FileService($this->pdo);
    }

    public function checkIsbnExists(string $isbn): bool
    {
        $qb = BookQuery::minimal()
            ->where(BookCriteria::byIsbn(alias: 'b'))
            ->bind(':isbn', $isbn);

        return $this->bookRepository->count($qb) !== 0;
    }

    public function getBookProductDetails(string $isbn): ?Book
    {
        $qb = BookQuery::asBookDetails();
        $qb->where(BookCriteria::byIsbn(alias: 'b')
            ->and(BookCriteria::notSoftDeleted(alias: 'b'))
            ->and(BookCriteria::notSoftDeleted(alias: 'wb')))
            ->bind(':isbn', $isbn);

        $book = $this->bookRepository->getOne($qb);
        if ($book == null)
            return null;

        return $book;
    }

    public function getBookById(int $id): ?Book
    {
        $qb = BookQuery::asBookAdminDetails();
        $qb->where(BookCriteria::byId(alias: 'b')
            ->and(BookCriteria::notSoftDeleted(alias: 'b')))
            ->bind(':id', $id);

        $book = $this->bookRepository->getOne($qb);
        if ($book == null)
            return null;

        return $book;
    }

    /**
     * @return Category[]
     */
    public function getAllCategories(): array
    {
        return $this->bookRepository->get(CategoryQuery::minimal());
    }

    /**
     * @param int|Work $work
     * @return RatingDTO[]
     */
    public function getRatings(int|Work $work): array
    {
        if ($work instanceof Work) $work = $work->id;

        $qb = RatingQuery::withFullDetails();
        $qb->where(RatingCriteria::byWork())
            ->bind(':work_id', $work);

        return array_map(
            [RatingDTO::class, 'fromRating'],
            $this->ratingRepository->get($qb)
        );
    }

    public function getRatingSummary(int|Work $work): RatingSummaryDTO
    {
        if ($work instanceof Work) $work = $work->id;

        return $this->ratingRepository->getRatingSummary($work);
    }

    /**
     * @param BookSearchDTO $dto
     * @return PageResultDTO<Book>
     */
    public function search(BookSearchDTO $dto): PageResultDTO
    {
        $qb = BookQuery::asBookListing();

        $predicates = BookCriteria::notSoftDeleted(alias: 'b');

        // is query string an isbn string?
        if ($dto->isIsbnQuery()) {
            $predicates = $predicates->and(BookCriteria::byIsbn(alias: 'b'));
            $qb->bind(':isbn', $dto->query);
        } else if ($dto->query !== null) {
            $predicates = $predicates->and(WorkCriteria::byTitleLike(alias: 'w'));
            $qb->bind(':title', '%' . $dto->query . '%');
        }

        if ($dto->categoryId !== null) {
            $predicates = $predicates->and(CategoryCriteria::byId(':category_id', alias: 'c'));
            $qb->bind(':category_id', $dto->categoryId);
        }

        if ($dto->format !== null) {
            $predicates = $predicates->and(BookCriteria::byFormat(alias: 'b'));
            $qb->bind(':format', $dto->format->name);
        }

        if ($dto->minPrice !== null && $dto->maxPrice !== null) {
            $predicates = $predicates->and(PriceCriteria::byAmountBetween(alias: 'p'));
            $qb->bind(':min', $dto->minPrice);
            $qb->bind(':max', $dto->maxPrice);
        }

        if ($dto->authorId !== null) {
            $predicates = $predicates->and(AuthorCriteria::byId('author_id', alias: 'ba'));
            $qb->bind(':author_id', $dto->authorId);
        }

        if ($dto->publisher !== null) {
            $predicates = $predicates->and(BookCriteria::byPublisher(alias: 'b'));
            $qb->bind(':publisher', $dto->publisher);
        }

        if ($dto->language !== null) {
            $predicates = $predicates->and(BookCriteria::byLanguage(alias: 'b'));
            $qb->bind(':language', $dto->language);
        }

        if ($dto->option) {
            $direction = $dto->option->getDirection();

            $property = match ($dto->option) {
                BookSearchSortOption::RELEVANCE => 'b.id',
                BookSearchSortOption::PRICE_ASC, BookSearchSortOption::PRICE_DESC => 'p.amount',
                BookSearchSortOption::TITLE_ASC, BookSearchSortOption::TITLE_DESC => 'w.title',
                BookSearchSortOption::PUBLISHED_ASC, BookSearchSortOption::PUBLISHED_DESC => 'b.publication_date',
            };

            $qb->orderBy($property, $direction);
        }

        $qb->where($predicates);
        $qb->page($dto->toPageRequest());

        $this->pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
        try {
            return new PageResultDTO(
                $this->bookRepository->get($qb),
                $this->bookRepository->count($qb),
                $dto->toPageRequest()
            );
        } finally {
            $this->pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
        }
    }

    /**
     * @param SearchDTO $dto
     * @return PageResultDTO<Work>
     */
    public function searchWork(SearchDTO $dto): PageResultDTO
    {
        $qb = WorkQuery::minimal();
        $qb->where(WorkCriteria::byTitleLike(alias: 'w'))
            ->bind(':title', '%' . $dto->query . '%');
        $qb->page($dto->toPageRequest());


        $this->pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
        try {
            return new PageResultDTO(
                $this->bookRepository->get($qb),
                $this->bookRepository->count($qb),
                $dto->toPageRequest()
            );
        } finally {
            $this->pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
        }
    }


    /**
     * @param SearchDTO $dto
     * @return PageResultDTO<Author>
     */
    public function searchAuthor(SearchDTO $dto): PageResultDTO
    {
        $qb = AuthorQuery::minimal();
        $qb->where(AuthorCriteria::byNameMatch(alias: 'a'))
            ->bind(':name', '%' . $dto->query . '%');
        $qb->page($dto->toPageRequest());

        $this->pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
        try {
            return new PageResultDTO(
                $this->bookRepository->get($qb),
                $this->bookRepository->count($qb),
                $dto->toPageRequest()
            );
        } finally {
            $this->pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
        }
    }

    /**
     * @throws ConflictException
     * @throws Throwable
     */
    public function createBook(BookCreateDTO $dto): Book
    {
        if ($this->checkIsbnExists($dto->isbn))
            throw new ConflictException(['message' => 'A book with the isbn ' . $dto->isbn . ' already exists.']);

        $book = $dto->toBook();

        $this->pdo->beginTransaction();
        try {
            $this->bookRepository->insert($book);
            foreach ($book->authors as $author)
                $this->bookRepository->insertBookAuthor($author);
            foreach ($book->inventories as $inventory)
                $this->bookRepository->insertInventory($inventory);
            foreach ($book->prices as $price)
                $this->bookRepository->insertPrice($price);
            foreach ($book->costs as $cost)
                $this->bookRepository->insertCost($cost);
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
        $this->pdo->commit();

        return $book;
    }

    /**
     * @throws NotFoundException
     */
    public function updateBook(BookUpdateDTO $dto): Book
    {
        $qb = BookQuery::minimal();
        $qb->where(BookCriteria::byId(alias: 'b'))
            ->bind(':id', $dto->id);

        $book = $this->bookRepository->getOne($qb);
        if ($book === null)
            throw new NotFoundException();

        $dto->update($book);

        $this->bookRepository->update($book);

        return $book;
    }

    /**
     * @throws ConflictException
     */
    public function deleteBook(int $bookId): void
    {
        $this->pdo->beginTransaction();

        try {
            $this->bookRepository->deleteAllBookAuthor($bookId);
            $this->bookRepository->delete($bookId);
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw new ConflictException();
        }

        $this->pdo->commit();
    }

    public function softDeleteBook(int $bookId): void
    {
        $this->bookRepository->softDelete($bookId);
    }

    public function addAuthor(int $bookId, int $authorId, AuthorDefinitionType $type): void
    {
        $ad = new AuthorDefinition();
        $ad->book = new Book();
        $ad->book->id = $bookId;
        $ad->author = new Author();
        $ad->author->id = $authorId;
        $ad->type = $type;

        $this->bookRepository->insertBookAuthor($ad);
    }

    public function removeAuthor(int $bookId, int $authorId): void
    {
        $ad = new AuthorDefinition();
        $ad->book = new Book();
        $ad->book->id = $bookId;
        $ad->author = new Author();
        $ad->author->id = $authorId;

        $this->bookRepository->deleteBookAuthor($ad);
    }

    public function insertInventory(int $bookId, InventoryLocation $location, int $quantity): void
    {
        $inventory = new Inventory();
        $inventory->book = new Book();
        $inventory->book->id = $bookId;
        $inventory->location = $location;
        $inventory->quantity = $quantity;

        $this->bookRepository->insertInventory($inventory);
    }

    public function updateInventory(int $inventoryId, int $quantity): void
    {
        $inventory = new Inventory();
        $inventory->id = $inventoryId;
        $inventory->quantity = $quantity;

        $this->bookRepository->updateInventory($inventory);
    }

    public function setNewPrice(int $bookId, int $amount): void
    {
        $price = new Price();
        $price->book = new Book();
        $price->book->id = $bookId;
        $price->amount = $amount;
        $price->fromDate = new DateTime();
        $price->thruDate = null;
        $price->comment = null;

        $this->bookRepository->setNewPrice($price);
    }

    public function setNewCost(int $bookId, int $amount): void
    {
        $cost = new Cost();
        $cost->book = new Book();
        $cost->book->id = $bookId;
        $cost->amount = $amount;
        $cost->fromDate = new DateTime();
        $cost->thruDate = null;
        $cost->comment = null;

        $this->bookRepository->setNewCost($cost);
    }

    /**
     * @param int $bookId
     * @param PhpFile[] ...$files
     * @return BookImage[]
     * @throws ConflictException
     * @throws BadRequestException
     * @throws UnprocessableEntityException
     * @throws ContentTooLargeException
     * @throws UnauthorizedException
     * @throws NotFoundException
     */
    public function uploadImage(int $bookId, array ...$files): array
    {
        /** @var QueryBuilder<Book> $qb */
        $qb = new QueryBuilder();
        $qb->from(Book::class, 'b')
            ->leftJoin('images', 'bi')
            ->where(BookCriteria::byId(alias: 'b'))
            ->bind(':id', $bookId);

        $book = $this->bookRepository->getOne($qb);
        if ($book === null)
            throw new NotFoundException();

        $i = max(array_merge(array_map(
                fn(BookImage $image) => $image->imageOrder,
                $book->images
            ), [-1])) + 1;

        $images = [];
        $this->pdo->beginTransaction();

        try {
            foreach ($files as $file) {
                $file = $this->fileService->uploadImage($file);

                $image = new BookImage();
                $image->file = $file;
                $image->book = new Book();
                $image->book->id = $bookId;
                $image->imageOrder = $i++;

                $this->bookRepository->insertImage($image);
                $images[] = $image;
            }
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw $e;
        }
        $this->pdo->commit();

        return $images;
    }

    /**
     * @throws NotFoundException
     */
    public function moveImage(int $bookId, int $fileId, int $targetPosition): void
    {
        /** @var QueryBuilder<Book> $qb */
        $qb = new QueryBuilder();
        $qb->from(Book::class, 'b')
            ->join('images', 'bi')
            ->where(BookCriteria::byId(alias: 'b'))
            ->bind(':id', $bookId);

        $book = $this->bookRepository->getOne($qb);
        if ($book === null)
            throw new NotFoundException();

        $book->normalizeOrder();
        $key = array_find_key($book->images, fn(BookImage $image) => $image->file->id === $fileId);
        if ($key === null)
            throw new NotFoundException();

        array_move_elem($book->images, $key, $targetPosition);
        $book->normalizeOrder();

        foreach ($book->images as $image)
            $this->bookRepository->updateImageOrder($image);
    }

    public function removeImage(int $bookId, int $imageId): void
    {
        $image = new BookImage();
        $image->file = new File();
        $image->file->id = $imageId;
        $image->book = new Book();
        $image->book->id = $bookId;

        $this->bookRepository->removeImage($image);
    }

    public function createWorkFromTitle(string $title): Work
    {
        $work = new Work();
        $work->slug = $this->slugify($title);
        $work->title = $title;
        $work->author = null;
        $work->defaultEdition = null;

        try {
            $this->bookRepository->insertWork($work);
            return $work;
        } catch (PDOException $e) {
            /** @var QueryBuilder<Work> $qb */
            $qb = new QueryBuilder();
            $qb->from(Work::class, 'w')
                ->where('slug = :slug')
                ->bind(':slug', $work->slug);

            $work = $this->bookRepository->getOne($qb);
            assert($work !== null);
            return $work;
        }
    }

    public function createAuthorFromName(string $name): Author
    {
        $author = new Author();
        $author->slug = $this->slugify($name);
        $author->name = $name;
        $author->description = null;
        $author->image = null;

        try {
            $this->bookRepository->insertAuthor($author);
            return $author;
        } catch (PDOException) {
            /** @var QueryBuilder<Author> $qb */
            $qb = new QueryBuilder();
            $qb->from(Author::class, 'a')
                ->where('slug = :slug')
                ->bind(':slug', $author->slug);

            $author = $this->bookRepository->getOne($qb);
            assert($author !== null);
            return $author;
        }
    }
}
