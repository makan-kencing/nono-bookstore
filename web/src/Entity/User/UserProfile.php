<?php

declare(strict_types=1);

namespace App\Entity\User;

use AllowDynamicProperties;
use App\Orm\Attribute\Id;
use App\Orm\Attribute\OneToOne;
use App\Orm\Entity;
use DateTime;

#[AllowDynamicProperties] class UserProfile extends Entity
{
    #[Id]
    #[OneToOne]
    public User $user;

    public ?string $contactNo;

    public ?DateTime $dob;
}
