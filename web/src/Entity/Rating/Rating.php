<?php

declare(strict_types=1);

namespace App\Entity\Rating;

use App\Entity\ABC\Entity;
use App\Entity\Book\Book;
use App\Entity\User\User;
use DateTime;

class Rating extends Entity
{
    public ?int $id;
    public Book $book;
    public User $user;
    public int $rating;
    public string $title;
    public string $content;
    /**
     * @var Reply[]
     */
    public ?array $replies;
    public DateTime $ratedAt;
}
