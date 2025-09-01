<?php

declare(strict_types=1);

namespace App\Router;

use App\Entity\User\UserRole;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
readonly class RequireAuth
{
    /**
     * @param UserRole[] $allowedRoles
     * @param UserRole[] $disallowedRoles
     * @param AuthRule $rule
     * @param bool $redirect
     */
    public function __construct(
        public array $allowedRoles = [],
        public array $disallowedRoles = [],
        public AuthRule $rule = AuthRule::HIGHER_OR_EQUAL,
        public bool $redirect = true
    ) {
    }

    public function check(UserRole $role): bool
    {
        if (in_array($role, $this->disallowedRoles))
            return false;

        if (!$this->allowedRoles)
            return true;

        return array_any(
            $this->allowedRoles,
            fn($allowedRole) => $this->rule->check($role, $allowedRole)
        );
    }
}
