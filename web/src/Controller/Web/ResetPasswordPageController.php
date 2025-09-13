<?php

namespace App\Controller\Web;

use App\Router\Method\GET;
use App\Router\Path;

#[Path('/reset-password')]
readonly class ResetPasswordPageController extends WebController
{
    #[GET]
    public function show(): void
    {
        $selector = $_GET['selector'] ?? '';
        $token = $_GET['token'] ?? '';

        if (!$selector || !$token) {
            die('Invalid reset link');
        }

        echo $this->render(
            'webstore/account/reset-password.php',
            [
                'selector' => $selector,
                'token' => $token
            ]
        );
    }
}

