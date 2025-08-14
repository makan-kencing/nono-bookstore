<?php

declare(strict_types=1);

namespace App\Entity\Rating;

use App\Entity\Book\Book;
use App\Entity\User\User;
use App\Orm\Entity;
use App\Orm\Id;
use App\Orm\ManyToOne;
use App\Orm\OneToMany;
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
    #[OneToMany(Reply::class, mappedBy: 'rating', optional: true)]
    public array $replies;
}
