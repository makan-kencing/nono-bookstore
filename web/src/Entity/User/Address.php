<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Orm\Attribute\Id;
use App\Orm\Attribute\ManyToOne;
use App\Orm\Entity;

class Address extends Entity
{
    #[Id]
    public ?int $id;

    #[ManyToOne]
    public User $user;

    public string $address1;

    public ?string $address2;

    public ?string $address3;

    public string $state;

    public string $postcode;

    public string $country;
}
