<?php

namespace App\Controller\Web;

use App\Exception\NotFoundException;
use App\Router\Method\GET;
use App\Router\Path;

#[Path('/reset-password')]
readonly class ResetPasswordPageController extends WebController
{
    /**
     * @throws NotFoundException
     */
    #[GET]
    public function show(): void
    {
        $selector = $_GET['selector'] ?? '';
        $token = $_GET['token'] ?? '';

        if (!$selector || !$token)
            throw new NotFoundException();

        echo $this->render(
            'webstore/account/reset-password.php',
            [
                'selector' => $selector,
                'token' => $token
            ]
        );
    }
}

