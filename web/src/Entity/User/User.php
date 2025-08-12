<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Entity\ABC\Entity;
use App\Entity\ABC\IdentifiableEntity;
use App\Entity\Book\Book;
use App\Entity\Cart\Cart;
use App\Entity\Order\Order;

class User extends IdentifiableEntity
{
    public string $username;
    public string $email;
    public string $hashedPassword;
    public UserRole $role;
    public bool $isVerified = false;
    public ?Address $defaultAddress;
    public ?UserProfile $profile;
    public ?Membership $membership;

    public ?Cart $cart;
    /** @var Address[] */
    public array $addresses;
    /** @var Book[] */
    public array $wishlist;
    /** @var Order[] */
    public array $orders;
}
