<?php

declare(strict_types=1);

namespace App\Controller;

use App\Router\Method\GET;
use App\Router\Path;

#[Path('/author')]
readonly class AuthorController extends Controller
{
    /**
     * @param string $slug
     * @return void
     */
    #[GET]
    #[Path('/{slug}')]
    public function viewAuthor(string $slug): void
    {
        echo $this->render('author.php');
    }
}
