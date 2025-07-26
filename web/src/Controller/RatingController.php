<?php

declare(strict_types=1);

namespace App\Controller;

readonly class RatingController extends Controller
{
    public function submitRating(): void
    {
        header('Content-Type: application/json');
    }

    public function editRating(): void
    {
        header('Content-Type: application/json');
    }

    public function deleteRating(): void
    {
        header('Content-Type: application/json');
    }
}
