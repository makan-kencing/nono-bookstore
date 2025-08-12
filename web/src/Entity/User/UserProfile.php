<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Entity\ABC\Entity;
use App\Entity\ABC\ExtensionEntity;
use DateTime;

class UserProfile extends ExtensionEntity
{
    public User $user;
    public ?string $contactNo;
    public ?DateTime $dob;
}
