<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\View;
use App\Entity\User\UserRole;
use PDO;

abstract readonly class ProtectedController extends Controller
{
    /** @var UserRole[] */
    public const array ALLOWED_ROLES = [UserRole::STAFF];
    public const bool PROMPT_LOGIN = true;

    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);

        // TODO: add role checking and redirection
    }
}
