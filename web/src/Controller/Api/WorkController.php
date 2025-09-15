<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Core\View;
use App\DTO\Request\SearchDTO;
use App\Entity\User\UserRole;
use App\Exception\ConflictException;
use App\Router\AuthRule;
use App\Router\Method\GET;
use App\Router\Method\POST;
use App\Router\Path;
use App\Router\RequireAuth;
use App\Service\BookService;
use PDO;

#[Path('/api/work')]
readonly class WorkController extends ApiController
{
    private BookService $bookService;

    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);
        $this->bookService = new BookService($pdo);
    }

    #[GET]
    #[Path('/{workId}/ratings')]
    public function getRatings(string $workId): void
    {
        $ratings = $this->bookService->getRatings((int)$workId);

        header('Content-Type: application/json');
        echo json_encode($ratings);
    }

    #[GET]
    #[Path('/{workId}/rating/summary')]
    public function getRatingSummary(string $workId): void
    {
        $summary = $this->bookService->getRatingSummary((int)$workId);

        header('Content-Type: application/json');
        echo json_encode($summary);
    }

    #[GET]
    #[Path('/options/{query}')]
    #[RequireAuth([UserRole::STAFF], rule: AuthRule::HIGHER_OR_EQUAL, redirect: false)]
    public function getOptions(string $query): void
    {
        $dto = new SearchDTO($query);

        $page = $this->bookService->searchWork($dto);

        header('Content-Type: text/html');
        foreach ($page->items as $item)
            echo "<option value='$item->id'>$item->title</option>";
    }

    #[POST]
    #[Path('/title/{title}')]
    #[RequireAuth([UserRole::STAFF], rule: AuthRule::HIGHER_OR_EQUAL, redirect: false)]
    public function createFromTitle(string $title): void
    {
        $work = $this->bookService->createWorkFromTitle($title);

        header('Content-Type: application/json');
        echo json_encode([
            'id' => $work->id,
            'slug' => $work->slug,
            'title' => $work->title
        ]);
    }
}
