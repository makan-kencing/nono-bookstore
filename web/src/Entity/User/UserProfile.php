<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Orm\Entity;
use App\Orm\Id;
use App\Orm\OneToOne;
use DateTime;

class UserProfile extends Entity
{
    #[Id]
    public ?int $userId;

    #[OneToOne]
    public User $user;

    public ?string $contactNo;

    public ?DateTime $dob;
}
