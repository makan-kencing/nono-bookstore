<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Entity\Book\Work;
use App\Entity\Cart\Cart;
use App\Entity\Cart\WishlistItem;
use App\Entity\File;
use App\Entity\Order\Order;
use App\Entity\Rating\Rating;
use App\Entity\Rating\Reply;
use App\Orm\Attribute\Id;
use App\Orm\Attribute\OneToMany;
use App\Orm\Attribute\OneToOne;
use App\Orm\Entity;

class User extends Entity
{
    #[Id]
    public ?int $id;

    public string $username;

    public string $email;

    public string $hashedPassword;

    public UserRole $role;

    public bool $isVerified;

    public bool $isBlocked;

    public ?string $totpSecret;

    #[OneToOne]
    public ?File $image;

    #[OneToOne]
    public ?Address $defaultAddress;

    #[OneToOne(mappedBy: 'user')]
    public ?UserProfile $profile;

    #[OneToOne(mappedBy: 'user')]
    public ?Membership $membership;

    #[OneToOne(mappedBy: 'user')]
    public ?Cart $cart;

    /** @var Address[] */
    #[OneToMany(Address::class, mappedBy: 'user')]
    public array $addresses;

    /** @var Work[] */
    #[OneToMany(WishlistItem::class, mappedBy: 'user')]
    public array $wishlist;

    /** @var Order[] */
    #[OneToMany(Order::class, mappedBy: 'user')]
    public array $orders;

    /** @var Rating[] */
    #[OneToMany(Rating::class, mappedBy: 'user')]
    public array $ratings;

    /** @var Reply[] */
    #[OneToMany(Reply::class, mappedBy: 'user')]
    public array $replies;

    /** @var File[] */
    #[OneToMany(File::class, mappedBy: 'user')]
    public array $uploadedFiles;

    /** @var UserSecurityEvent[] */
    #[OneToMany(UserSecurityEvent::class, mappedBy: 'user')]
    public array $securityEvents;

    /** @var UserToken[] */
    #[OneToMany(UserToken::class, mappedBy: 'user')]
    public array $tokens;
}
