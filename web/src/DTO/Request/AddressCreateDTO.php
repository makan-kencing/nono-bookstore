<?php

namespace App\DTO\Request;

use App\Controller\Web\AddressesController;
use App\DTO\DTO;
use App\Entity\User\Address;
use App\Entity\User\UserRole;
use App\Exception\BadRequestException;
use App\Exception\UnprocessableEntityException;
use Throwable;

readonly class AddressCreateDTO extends RequestDTO
{
    public function __construct(
        public string $name,
        public string $address1,
        public string $address2,
        public string $address3,
        public string $state,
        public string $postcode,
        public string $country,
        public string $phone,
        public bool $default
    ){
    }

    public static function jsonDeserialize(mixed $json): AddressCreateDTO
    {
        assert(is_array($json));

        try {
            return new self(
                $json['name'],
                $json['address1'],
                $json['address2'],
                $json['address3'],
                $json['state'],
                $json['postcode'],
                $json['country'],
                $json['phone_number'],
                array_key_exists('default', $json)
            );
        } catch (Throwable) {
            throw new BadRequestException();
        }
    }


    public function validate(): void
    {
        $rules = [];
        if (!preg_match('/^[0-9\-]+$/', $this->postcode))
            $rules[] = [
                'field' => 'postcode',
                'type' => 'postcode',
                'reason' => 'Postcode can only contains digits and dash.'
            ];
        if (!preg_match('/^[0-9\-+()]+$/', $this->phone))
            $rules[] = [
                'field' => 'phone_number',
                'type' => 'phone_number',
                'reason' => 'Phone number can only contains digits, dash, plus, parentheses and spaces.'
            ];
        if (strlen($this->phone) > 15)
            $rules[] = [
                'field' => 'phone_number',
                'type' => 'phone_number',
                'reason' => 'Phone number is maximum 15 characters.'
            ];

        if ($rules)
            throw new UnprocessableEntityException($rules);
    }

    public function toAddress(): Address
    {
        $address = new Address();
        $address->name = $this->name;
        $address->address1 = $this->address1;
        $address->address2 = $this->address2;
        $address->address3 = $this->address3;
        $address->state = $this->state;
        $address->postcode = $this->postcode;
        $address->country = $this->country;
        $address->phoneNumber = $this->phone;

        return $address;
    }
}
