<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\ABC\Entity;
use App\Entity\User\User;
use DateTime;

class File extends Entity
{
    public ?int $id;
    public string $filename;
    public string $mimetype;
    public ?string $alt;
    public string $filepath;
    public DateTime $createdAt;
    public User $createdBy;
}
