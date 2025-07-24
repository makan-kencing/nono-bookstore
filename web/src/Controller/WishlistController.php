<?php

declare(strict_types=1);

namespace App\Controller;

readonly class WishlistController extends Controller
{
    public function viewWishlist(): void
    {
        echo $this->render('wishlist.php');
    }

    public function addItem(): void
    {
    }

    public function removeItem(): void
    {
    }
}
