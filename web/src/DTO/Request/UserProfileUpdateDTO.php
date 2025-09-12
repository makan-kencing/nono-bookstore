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
        public ?string   $contactNo = null,
        public ?DateTime $dob = null,
    )
    {
    }

    /**
     * @inheritDoc
     */
    public static function jsonDeserialize(mixed $json): self
    {
        assert(is_array($json));
        try {
            $contactNo = array_get($json, 'contact_no');
            $dobString = array_get($json, 'dob');
            $dob = null;

            if ($dobString) {
                $dob = DateTime::createFromFormat('Y-m-d', $dobString);
                if (!$dob) {
                    throw new BadRequestException(); // invalid date format
                }
            }

            return new self(
                $contactNo,
                $dob
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

        if ($rules) {
            throw new UnprocessableEntityException($rules);
        }
    }

    public function updateProfile(UserProfile $userProfile): void
    {
        if ($this->contactNo) {
            $userProfile->contactNo = $this->contactNo;
        }

        if ($this->dob) {
            $userProfile->dob = $this->dob;
        }
    }
}
