<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Orm\Entity;
use DateTime;

class UserSecurityEvent extends Entity
{
    public ?int $id;

    public User $user;

    public UserSecurityEventType $event;

    public string $data;

    public DateTime $createdAt;
}
