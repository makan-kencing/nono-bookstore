<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Core\View;
use App\Router\Method\GET;
use App\Router\Path;
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
        $ratings = $this->bookService->getRatings((int) $workId);

        echo json_encode($ratings);
    }

    #[GET]
    #[Path('/{workId}/rating/summary')]
    public function getRatingSummary(string $workId): void
    {
        $summary = $this->bookService->getRatingSummary((int) $workId);

        echo json_encode($summary);
    }
}
