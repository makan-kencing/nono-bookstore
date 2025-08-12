<?php

declare(strict_types=1);

namespace App\Entity\Rating;

use App\Entity\ABC\Entity;
use App\Entity\ABC\IdentifiableEntity;
use App\Entity\Book\Book;
use App\Entity\User\User;
use DateTime;

class Rating extends IdentifiableEntity
{
    public Book $book;
    public User $user;
    public int $rating;
    public string $title;
    public string $content;
    public DateTime $ratedAt;
    /** @var Reply[] */
    public array $replies;
}
