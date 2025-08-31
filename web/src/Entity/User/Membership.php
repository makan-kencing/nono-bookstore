<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Entity\Trait\TimeLimited;
use App\Orm\Attribute\Id;
use App\Orm\Attribute\OneToOne;
use App\Orm\Entity;

class Membership extends Entity
{
    use TimeLimited;

    #[Id]
    #[OneToOne]
    public User $user;

    public string $cardNo;
}
