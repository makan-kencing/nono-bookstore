<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\ABC\IdentifiableEntity;
use App\Entity\User\User;
use DateTime;

class File extends IdentifiableEntity
{
    public User $user;
    public string $filename;
    public string $mimetype;
    public ?string $alt;
    public string $filepath;
    public DateTime $createdAt;
}
