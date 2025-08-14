<?php

declare(strict_types=1);

namespace App\Entity\Rating;

use App\Entity\User\User;
use App\Orm\Entity;
use App\Orm\Id;
use App\Orm\ManyToOne;
use DateTime;

class Reply extends Entity
{
    #[Id]
    public ?int $id;

    #[ManyToOne]
    public Rating $rating;

    #[ManyToOne]
    public User $user;

    public string $content;

    public DateTime $repliedAt;
}
