<?php

namespace App\DTO\Request;

use App\Entity\User\User;
use App\Entity\User\UserProfile;
use App\Exception\BadRequestException;
use App\Exception\UnprocessableEntityException;
use DateTime;
use Throwable;
use function App\Utils\array_get;

readonly class UserProfileUpdateDTO extends RequestDTO
{
    public function __construct(
        public ?string $username = null,
        public ?string $email = null,
        public ?string   $contactNo = null,
        public ?DateTime $dob = null,
    ) {
    }

    /**
     * @inheritDoc
     */
    public static function jsonDeserialize(mixed $json): self
    {
        assert(is_array($json));
        try {
            return new self(
                $json['username'] ?? null ?: null,
                $json['email'] ?? null ?: null,
                $json['contact_no'] ?? null ?: null,
                DateTime::createFromFormat('Y-m-d', $json['dob']) ?: null
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

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL))
            $rules[] = [
                "field" => "email",
                "type" => "email",
                "reason" => "Invalid email format"
            ];

        if ($this->contactNo !== null && !preg_match('/^[0-9+\-\s]{7,20}$/', $this->contactNo)) {
            $rules[] = [
                "field" => "contact_no",
                "type" => "format",
                "reason" => "Invalid contact number format"
            ];
        }

        if ($this->dob !== null && $this->dob > new DateTime()) {
            $rules[] = [
                "field" => "dob",
                "type" => "invalid",
                "reason" => "Date of birth cannot be in the future"
            ];
        }

        if ($rules)
            throw new UnprocessableEntityException($rules);
    }

    public function update(User $user): void
    {
        $this->updateUser($user);
        $this->updateProfile($user->profile);
    }

    public function updateUser(User $user): void
    {
        if ($this->username)
            $user->username = $this->username;

        if ($this->email)
            $user->email = $this->email;
    }

    public function updateProfile(UserProfile $userProfile): void
    {
        if ($this->contactNo)
            $userProfile->contactNo = $this->contactNo;

        if ($this->dob)
            $userProfile->dob = $this->dob;
    }
}
