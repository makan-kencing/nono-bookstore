<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Orm\Entity;
use App\Orm\Id;
use App\Orm\ManyToOne;
use DateTime;

class UserToken extends Entity
{
    #[Id]
    public ?int $id;

    #[ManyToOne]
    public User $user;

    public UserTokenType $type;

    public string $selector;

    public string $token;

    public DateTime $expiresAt;
}
