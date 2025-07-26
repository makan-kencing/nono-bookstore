<?php

declare(strict_types=1);

namespace App\Controller;

readonly class ReplyController extends Controller
{
    public function submitReply(): void
    {
        header('Content-Type: application/json');
    }

    public function editReply(): void
    {
        header('Content-Type: application/json');
    }

    public function deleteReply(): void
    {
        header('Content-Type: application/json');
    }
}
