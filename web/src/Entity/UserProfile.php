<?php

namespace App\Entity;

use DateTime;

class UserProfile
{
    private ?string $contact_no {
        get => $this->contact_no;
        set => $this->contact_no = $value;
    }

    private ?DateTime $dob {
        get => $this->dob;
    }

    public function setDob(DateTime $dob): void
    {
        $dob = $dob->setTime(0, 0, 0);
        $this->dob = $dob;
    }
}