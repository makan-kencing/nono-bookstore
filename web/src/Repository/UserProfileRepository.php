<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User\UserProfile;

readonly class UserProfileRepository extends Repository
{
    public function insertProfile(UserProfile $userProfile): void{


        $stmt = $this->conn->prepare('
        Insert into user_profile (user_id,contact_no,dob)VALUES(:user_id,:contact_no,:dob);
        ');
        $stmt->bindValue(':user_id', $userProfile->id);
        $stmt->bindValue(':contact_no', $userProfile->contactNo);
        $stmt->bindValue(':dob', $userProfile->dob);
    }

    public function updateProfile(UserProfile $userProfile): void{
        $stmt = $this->conn->prepare('
        Update user_profile
        SET contact_no=:contact_no,dob=:dob
        WHERE user_id=:user_id;
        ');
        $stmt->bindValue(':user_id', $userProfile->id);
        $stmt->bindValue(':contact_no', $userProfile->contactNo);
        $stmt->bindValue(':dob', $userProfile->dob);
    }
}
