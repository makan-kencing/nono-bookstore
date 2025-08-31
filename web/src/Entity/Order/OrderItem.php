<?php

declare(strict_types=1);

namespace App\Entity\Order;

use App\Entity\Cart\CartItem;
use App\Entity\Trait\Commentable;

class OrderItem extends CartItem
{
    use Commentable;

    public int $unitPrice;

    public int $quantity;
}
