<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User\UserProfile;

readonly class UserProfileRepository extends Repository
{
    public function insert(UserProfile $profile): void
    {
        $stmt = $this->conn->prepare('
        INSERT INTO user_profile (user_id, contact_no, dob)
        VALUES (:user_id, :contact_no, :dob)
    ');
        $stmt->bindValue(':user_id', $profile->user->id, \PDO::PARAM_INT);
        $stmt->bindValue(':contact_no', $profile->contactNo);
        $stmt->bindValue(':dob', $profile->dob?->format('Y-m-d'));
        $stmt->execute();
    }

    public function updateProfile(UserProfile $profile): void
    {
        $stmt = $this->conn->prepare('
        UPDATE user_profile
        SET contact_no = :contact_no,
            dob = :dob
        WHERE user_id = :user_id
    ');
        $stmt->bindValue(':user_id', $profile->user->id, \PDO::PARAM_INT);
        $stmt->bindValue(':contact_no', $profile->contactNo);
        // Convert DateTime to string
        $stmt->bindValue(':dob', $profile->dob?->format('Y-m-d'));
        $stmt->execute();
    }

}
