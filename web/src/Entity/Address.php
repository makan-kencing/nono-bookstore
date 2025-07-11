<?php

namespace App\Entity;

use App\Entity\Trait\Commetable;

class Address extends Entity
{
    use Commetable;

    private User $user {
        get => $this->user;
        set => $this->user;
    }

    private string $address1 {
        get => $this->address1;
        set => $this->address1;
    }

    private ?string $address2 {
        get => $this->address2;
        set => $this->address2;
    }

    private ?string $address3 {
        get => $this->address3;
        set => $this->address3;
    }

    private string $state {
        get => $this->state;
        set => $this->state;
    }

    private string $postcode {
        get => $this->postcode;
        set => $this->postcode;
    }

    private string $country {
        get => $this->country;
        set => $this->country;
    }

    private bool $is_default = false {
        get => $this->is_default;
        set => $this->is_default;
    }
}