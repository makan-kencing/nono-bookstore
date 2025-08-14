<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Entity\Trait\TimeLimited;
use App\Orm\Entity;
use App\Orm\Id;
use App\Orm\MapsId;
use App\Orm\OneToOne;

class Membership extends Entity
{
    use TimeLimited;

    #[Id]
    public ?int $userId;

    #[MapsId]
    #[OneToOne]
    public User $user;

    public string $cardNo;
}
