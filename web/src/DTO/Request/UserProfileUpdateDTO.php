<?php

namespace App\DTO\Request;

use App\DTO\Request\RequestDTO;
use App\Exception\BadRequestException;
use App\Exception\UnprocessableEntityException;
use Throwable;

readonly class UserProfileUpdateDTO extends RequestDTO
{
    public function __construct(
        public ?string $username = null,
        public ?string $email = null,
        public ?string $password = null,
        public ?string $contactNo = null,
        public ?string $dob = null,
    ) {}
    /**
     * @inheritDoc
     */
    public static function jsonDeserialize(mixed $json): self
    {
        if (!is_array($json)) throw new BadRequestException();
        try {
            return new self(
                $json['username'] ?? null,
                $json['email'] ?? null,
                $json['password'] ?? null,
                $json['contact_no'] ?? null,
                $json['dob'] ?? null,
            );
        } catch (Throwable) {
            throw new BadRequestException();
        }
    }

    /**
     * @inheritDoc
     */
    public function validate(): void
    {
        $rules = [];
        if ($this->email && !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $rules[] = [
                "field" => "email",
                "type" => "email",
                "reason" => "Invalid email format"
            ];
        }
        if ($rules) throw new UnprocessableEntityException($rules);
    }
}
