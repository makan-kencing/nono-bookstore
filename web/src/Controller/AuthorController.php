<?php

declare(strict_types=1);

namespace App\Controller;

readonly class AuthorController extends Controller
{
    /**
     * @param array<string, string> $pathVars
     * @return void
     */
    public function viewAuthor(array $pathVars): void
    {
        $slug = $pathVars['slug'];

        echo $this->render('author.php');
    }
}
