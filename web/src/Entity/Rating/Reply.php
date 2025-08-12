<?php

declare(strict_types=1);

namespace App\Entity\Rating;

use App\Entity\ABC\IdentifiableEntity;
use App\Entity\User\User;
use DateTime;

class Reply extends IdentifiableEntity
{
    public Rating $rating;
    public User $user;
    public string $content;
    public DateTime $repliedAt;
}
