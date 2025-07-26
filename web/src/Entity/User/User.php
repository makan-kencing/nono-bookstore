<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Entity\ABC\Entity;
use App\Entity\Cart\Cart;
use App\Entity\Cart\Wishlist;
use App\Entity\Order\Order;

class User extends Entity
{
    public ?int $id;
    public string $username;
    public string $email;
    public string $hashedPassword;
    public UserRole $role;
    public bool $isVerified = false;
    public ?Address $defaultAddress;
    public ?UserProfile $profile;
    /** @var Address[] */
    public ?array $addresses;
    public ?Membership $membership;
    public ?Wishlist $wishlist;
    public ?Cart $cart;
    /** @var Order[] */
    public ?array $orders;
}
