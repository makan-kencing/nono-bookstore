<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Router\Method\DELETE;
use App\Router\Method\POST;
use App\Router\Method\PUT;
use App\Router\Path;

#[Path('/api/reply')]
readonly class ReplyController extends ApiController
{
    #[POST]
    public function submitReply(): void
    {
        header('Content-Type: application/json');
    }

    #[PUT]
    public function editReply(): void
    {
        header('Content-Type: application/json');
    }

    #[DELETE]
    public function deleteReply(): void
    {
        header('Content-Type: application/json');
    }
}
