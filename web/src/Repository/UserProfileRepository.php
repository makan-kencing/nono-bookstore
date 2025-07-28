<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User\UserProfile;
use App\Repository\Repository;

/**
 * @extends Repository<UserProfile>
 */
readonly class UserProfileRepository extends Repository
{
    public function addUserProfile(UserProfile $userProfile): void{
        $stmt = $this->conn->prepare('
        Insert into user_profile (contact_no,dob)VALUES(:contact_no,:dob);
        ');
        $stmt->bindValue(':contact_no', $userProfile->contactNo);
        $stmt->bindValue(':dob', $userProfile->dob);
    }

    /*public function updateUserProfile(UserProfile $userProfile): void{
        $stmt = $this->conn->prepare('
        Update user_profile
        SET contact_no=:contact_no,dob=:dob
        WHERE id=:id;
        ');
    }*/


}
