<?php

declare(strict_types=1);

namespace App\Entity\Rating;

use App\Entity\Book\Book;
use App\Entity\User\User;
use App\Orm\Attribute\Id;
use App\Orm\Attribute\ManyToOne;
use App\Orm\Attribute\OneToMany;
use App\Orm\Entity;
use DateTime;

class Rating extends Entity
{
    #[Id]
    public ?int $id;

    #[ManyToOne]
    public Book $book;

    #[ManyToOne]
    public User $user;

    public int $rating;

    public string $title;

    public string $content;

    public DateTime $ratedAt;

    /** @var Reply[] */
    #[OneToMany(Reply::class, mappedBy: 'rating')]
    public array $replies;
}
