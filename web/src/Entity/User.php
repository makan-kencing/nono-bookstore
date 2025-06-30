<?php

namespace App\Entity;

use DateTime;

class User
{
    private  int $id;
    private string $username;
    private string $email;

    private string $hashed_password;

    private Role $role;

    private bool $is_verified = false;

    private string $last_ip;

    private DateTime $last_login;
}