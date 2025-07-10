<?php

namespace App\Entity;

class Address extends Entity
{
    private User $user {
        get => $this->user;
        set => $this->user = $value;
    }

    private string $address1 {
        get => $this->address1;
        set => $this->address1 = $value;
    }

    private ?string $address2 {
        get => $this->address2;
        set => $this->address2 = $value;
    }

    private ?string $address3 {
        get => $this->address3;
        set => $this->address3 = $value;
    }

    private string $state {
        get => $this->state;
        set => $this->state = $value;
    }

    private string $postcode {
        get => $this->postcode;
        set => $this->postcode = $value;
    }

    private string $country {
        get => $this->country;
        set => $this->country = $value;
    }

    private bool $is_default = false {
        get => $this->is_default;
        set => $this->is_default = $value;
    }
}