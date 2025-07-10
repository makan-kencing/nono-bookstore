<?php

namespace App\Entity;

enum UserRole
{
    case USER;
    case STAFF;
    case ADMIN;
    case OWNER;
}
