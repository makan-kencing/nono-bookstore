<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Entity\ABC\Entity;
use DateTime;

class UserProfile extends Entity
{
    public User $user;
    public ?string $contactNo;
    public ?DateTime $dob;
}
