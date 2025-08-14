<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Entity\Book\Book;
use App\Entity\Cart\Cart;
use App\Entity\Cart\WishlistItem;
use App\Entity\File;
use App\Entity\Order\Order;
use App\Entity\Rating\Rating;
use App\Entity\Rating\Reply;
use App\Orm\Entity;
use App\Orm\Id;
use App\Orm\OneToMany;
use App\Orm\OneToOne;

class User extends Entity
{
    #[Id]
    public ?int $id;

    public string $username;

    public string $email;

    public string $hashedPassword;

    public UserRole $role;

    public bool $isVerified = false;

    #[OneToOne(mappedBy: 'user')]
    public ?UserProfile $profile;

    #[OneToOne(mappedBy: 'user')]
    public ?Membership $membership;

    #[OneToOne(mappedBy: 'user')]
    public ?Cart $cart;

    #[OneToOne(mappedBy: 'user')]
    public ?Address $defaultAddress;

    /** @var Address[] */
    #[OneToMany(Address::class, mappedBy: 'user', optional: true)]
    public array $addresses;

    /** @var Book[] */
    #[OneToMany(WishlistItem::class, mappedBy: 'user', optional: true)]
    public array $wishlist;

    /** @var Order[] */
    #[OneToMany(Order::class, mappedBy: 'user', optional: true)]
    public array $orders;

    /** @var Rating[] */
    #[OneToMany(Rating::class, mappedBy: 'user', optional: true)]
    public array $ratings;

    /** @var Reply[] */
    #[OneToMany(Reply::class, mappedBy: 'user', optional: true)]
    public array $replies;

    /** @var File[] */
    #[OneToMany(File::class, mappedBy: 'user', optional: true)]
    public array $uploadedFiles;
}
