<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Orm\Entity;
use App\Orm\Id;
use App\Orm\ManyToOne;

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
