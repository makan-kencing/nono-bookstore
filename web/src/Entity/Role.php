<?php

namespace App\Entity;

enum Role
{
    case USER;
    case STAFF;
    case ADMIN;
    case OWNER;
}
