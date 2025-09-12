<?php

namespace App\Repository;

use App\DTO\Request\RequestDTO;
use App\Entity\User\Address;
use App\Entity\User\User;

readonly class UserAddressRepository extends Repository
{

    public function insert(Address $address): void{
        $stmt=$this->conn->prepare('
            INSERT INTO address (user_id, name, address1, address2, address3, state, postcode, country, phone_number)
            VALUE (:user_id, :name, :address1, :address2, :address3, :state, :postcode, :country, :phone_number)
        ');
        $stmt->execute([
            ':user_id' => $address->user->id,
            ':name' => $address->name,
            ':address1' => $address->address1,
            ':address2' => $address->address2,
            ':address3' => $address->address3,
            ':state' => $address->state,
            ':postcode' => $address->postcode,
            ':country' => $address->country,
            ':phone_number' => $address->phoneNumber
        ]);

        $address->id = (int) $this->conn->lastInsertId();
    }

    public function update(Address $address): void
    {
        $stmt = $this->conn->prepare('
            UPDATE address
            SET user_id = :user_id,
                name = :name,
                address1 = :address1,
                address2 = :address2,
                address3 = :address3,
                state = :state,
                postcode = :postcode,
                country = :country,
                phone_number = :phone_number
             WHERE id = :id
         ');
        $stmt->execute([
            ':id' => $address->id,
            ':user_id' => $address->user->id,
            ':name' => $address->name,
            ':address1' => $address->address1,
            ':address2' => $address->address2,
            ':address3' => $address->address3,
            ':state' => $address->state,
            ':postcode' => $address->postcode,
            ':country' => $address->country,
            ':phone_number' => $address->phoneNumber
        ]);
    }

    public function setDefault(Address $address): void
    {
        $stmt = $this->conn->prepare('
            UPDATE user
            SET default_address_id = :address_id
            WHERE id = :id
         ');
        $stmt->execute([
            ':id' => $address->user->id,
            ':address_id' => $address->id
        ]);
    }

    public function deleteAddress(int $id): void
    {
        $stmt=$this->conn->prepare('
        DELETE FROM address
        WHERE id = :id;
        ');
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
}
