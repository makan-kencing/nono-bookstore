<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Entity\ABC\Entity;

class Address extends Entity
{
    public ?int $id;
    public User $user;
    public string $address1;
    public ?string $address2;
    public ?string $address3;
    public string $state;
    public string $postcode;
    public string $country;
}
