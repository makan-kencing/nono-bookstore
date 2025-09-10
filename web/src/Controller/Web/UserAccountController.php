<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Router\Method\GET;
use App\Router\Path;
use App\Router\RequireAuth;

#[Path('/user')]
#[RequireAuth]
readonly class UserAccountController extends WebController
{
    #[GET]
    #[Path('/profile')]
    public function viewProfile(): void
    {

        echo $this->render('user/userProfile.php');
    }
}
