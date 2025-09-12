<?php

namespace App\DTO\Request;

use App\DTO\Request\RequestDTO;
use App\Entity\User\Address;
use App\Exception\BadRequestException;
use function App\Utils\array_get;

readonly class UserAddressDTO extends RequestDTO
{

    public function __construct(
        public ? string $name= null,
        public ? string $address1= null,
        public ? string $address2= null,
        public ? string $address3= null,
        public ? string $state= null,
        public ? string $postcode= null,
        public ? string $country= null,
        public ? string $phoneNumber= null,
    ){
    }

    /**
     * @throws BadRequestException
     * @inheritDoc
     */
    public static function jsonDeserialize(mixed $json): UserAddressDTO
    {
        assert(is_array($json));
        try {
            return new self(
                array_get($json, 'name'),
                array_get($json, 'address1'),
                array_get($json, 'address2'),
                array_get($json, 'address3'),
                array_get($json, 'state'),
                array_get($json, 'postcode'),
                array_get($json, 'country'),
                array_get($json, 'phoneNumber'),
            );
        }catch (Throwable){
            throw new BadRequestException();
        }
    }

    public function update(Address $address): void
    {
        if ($this->name){
            $address->name = $this->name;
        }

        if ($this->address1){
            $address->address1 = $this->address1;
        }

        if ($this->address2){
            $address->address2 = $this->address2;
        }

        if ($this->address3){
            $address->address3 = $this->address3;
        }

        if ($this->state){
            $address->state = $this->state;
        }

        if ($this->postcode){
            $address->postcode = $this->postcode;
        }

        if ($this->country){
            $address->country = $this->country;
        }

        if ($this->phoneNumber){
            $address->phoneNumber = $this->phoneNumber;
        }
    }

    public function validate(): void
    {
        // TODO: Implement validate() method.
    }
}
