<?php

declare(strict_types=1);

namespace App\Entity\Rating;

use App\Entity\ABC\Entity;
use App\Entity\User\User;

class Reply extends Entity
{
    public ?int $id;
    public Rating $rating;
    public User $user;
    public string $content;
}
