<?php

namespace App\Service;

use App\Entity\User\User;
use App\Entity\User\UserRole;
use App\Entity\User\UserStatus;

class SecurityService
{
    /**
     * @var string[]
     */
    public const array USERS = [
        'ST0001' => [
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => '123abc!!!', // hashed pass demo
            'role' => UserRole::ADMIN,
            'status' => 'active',
        ]
    ];

    public function register(string $username, string $email, string $password): void
    {
        // check password valid
        if (!$this->checkPasswordValidity($password)) {
            echo "Invalid password";
            return;
        }

        // check username already exists?
        if (isset(self::USERS[$username])) {
            echo "Username already exists";
            return;
        }

        // hash password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // create the account
        self::USERS[$username] = [
            'username' => $username,
            'email' => $email,
            'password' => $password, // Optional: hash here
            'role' => UserRole::USER,
            'status' => 'active',
        ];
    }

    public function login(string $email, string $password, bool $rememberMe = false, ?string $otp = null):void
    {
        // check username or email exists
        // check password correct
        // if brute forcing, lock account. RETURN LOCKED
        // if 2fa enabled, check otp, RETURN TWO_FACTOR_REQUIRED
        // Verify OTP logic would go here
        // set auth session, RETURN SUCCESS

    }

    public function logout(): void
    {
        // unset auth session
//        if (session_status() === PHP_SESSION_ACTIVE) {
//            session_destroy();
//        }
    }

    public function requestPasswordReset(): void
    {
        // Generate reset token
        // Store token with expiry
        // Send reset email
    }

    public function changePassword(string $email, string $newPassword): void
    {
        // check password valid
//        if (!$this->checkPasswordValidity($newPassword)) {
//            echo "Invalid password";
//        }
        // change password
    }

    public function lockAccount(string $email, int $duration): void
    {
    }

    public function unlockAccount(string $email): void
    {
    }

    public function sendEmailVerification(string $email): void
    {
        // Generate verification token
        // Store token
        // Send verification email
    }

    public function verifyEmail(string $email, string $token): void
    {
        // Verify token validity
        // Mark email as verified
    }

    public function checkPasswordValidity(string $password): void
    {
        // Minimum length
        // Contains uppercase
        // Contains lowercase
        // Contains numbers
        // Contains special characters
        // return true;
    }

    public function isLoggedIn(): bool
    {
        // return isset($_SESSION['user']);
    }

    public function hasRole(UserRole ...$role): bool
    {
//        if (!$this->isLoggedIn()) {
//            return false;
//        }
//
//        // Check if user has any of the specified roles
//        return false;
    }

    private function createSession(string $email): void
    {

    }
}
