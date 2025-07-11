<?php

namespace App\Entity\User;

use DateTime;

class UserProfile
{
    private ?string $contact_no {
        get => $this->contact_no;
        set => $this->contact_no;
    }

    private ?DateTime $dob {
        get => $this->dob;
        set {
            if ($value instanceof DateTime)
                $this->dob = $value->setTime(0, 0);
        }
    }
}