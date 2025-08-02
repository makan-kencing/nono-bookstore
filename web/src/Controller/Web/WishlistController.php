<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Router\Method\GET;
use App\Router\Path;

#[Path('/wishlist')]
readonly class WishlistController extends WebController
{
    #[GET]
    public function viewWishlist(): void
    {
        echo $this->render('wishlist.php');
    }
}
