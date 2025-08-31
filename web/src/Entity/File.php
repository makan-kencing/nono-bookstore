<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\User\User;
use App\Orm\Attribute\Id;
use App\Orm\Attribute\ManyToOne;
use App\Orm\Entity;
use DateTime;

class File extends Entity
{
    #[Id]
    public ?int $id;

    #[ManyToOne]
    public User $user;

    public string $filename;

    public string $mimetype;

    public ?string $alt;

    public string $filepath;

    public DateTime $createdAt;
}
