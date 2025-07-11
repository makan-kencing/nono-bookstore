<?php

namespace App\Entity;

use DateTime;

class User extends Entity
{
    private string $username {
        get => $this->username;
        set => $this->username;
    }

    private string $email {
        get => $this->email;
    }

    private string $hashed_password {
        get => $this->hashed_password;
        set => $this->hashed_password;
    }

    private UserRole $role {
        get => $this->role;
        set => $this->role;
    }

    private UserProfile $profile {
        get {
            $this->profile = $this->profile ?? new UserProfile();
            return $this->profile;
        }
        set => $this->profile;
    }

    private bool $is_verified = false {
        get => $this->is_verified;
        set => $this->is_verified;
    }

    private ?string $last_ip {
        get => $this->last_ip;
    }

    private ?DateTime $last_login {
        get => $this->last_login;
    }

    /**
     * Changes the User email and set to unverified.
     *
     * @param string $email The User's new email.
     * @return void
     */
    public function changeEmail(string $email): void
    {
        $this->email = $email;
        $this->is_verified = false;
    }

    /**
     * Logs the current User login.
     *
     * @param string $ip_address The ip address the User logged in from.
     * @return void
     */
    public function logLogin(string $ip_address): void
    {
        $this->last_ip = $ip_address;
        $this->last_login = new DateTime();
    }
}