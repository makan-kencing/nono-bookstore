<?php

declare(strict_types=1);

namespace App\Controller;

use App\Router\Method\GET;
use App\Router\Path;

#[Path('/wishlist')]
readonly class WishlistController extends ProtectedController
{
    #[GET]
    public function viewWishlist(): void
    {
        echo $this->render('wishlist.php');
    }
}
