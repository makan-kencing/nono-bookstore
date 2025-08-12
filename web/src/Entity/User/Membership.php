<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Entity\ABC\Entity;
use App\Entity\ABC\ExtensionEntity;
use App\Entity\ABC\Trait\TimeLimited;

class Membership extends ExtensionEntity
{
    use TimeLimited;

    public User $user;
    public string $cardNo;
}
