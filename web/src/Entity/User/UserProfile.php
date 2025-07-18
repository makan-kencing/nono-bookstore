<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Entity\ABC\Entity;
use DateTime;

class UserProfile extends Entity
{
    public ?string $contactNo;
    public ?DateTime $dob;
}
