<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Router\Method\GET;
use App\Router\Path;

#[Path('/admin/profile')]
//#[RequireAuth([UserRole::STAFF], rule: AuthRule::HIGHER_OR_EQUAL)]
readonly class AdminProfileController extends WebController
{
    #[GET]
    public function viewProfile(): void
    {
        echo $this->render('admin/adminProfile.php');
    }
}
