<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Router\Method\GET;
use App\Router\Path;

readonly class SecurityController extends WebController
{
    #[GET]
    #[Path('/login')]
    public function login(): void
    {
        if (!array_key_exists('redirect', $_GET)) {
            $this->redirect('/login?redirect=' . urlencode($_SERVER['HTTP_REFERER'] ?? '/'));
            return;
        }

        echo $this->render('auth/login.php');
    }

    #[GET]
    #[Path('/register')]
    public function register(): void
    {
        if (!array_key_exists('redirect', $_GET)) {
            $this->redirect('/register?redirect=' . urlencode($_SERVER['HTTP_REFERER'] ?? '/'));
            return;
        }

        echo $this->render('auth/register.php');
    }

    #[GET]
    #[Path('/logout')]
    public function logout(): void
    {
        $this->authService->logout();

        http_response_code(303);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    #[GET]
    #[Path('/register/redirect')]
    public function registerRedirect(): void
    {
        $selector = $_GET['selector'] ?? null;
        $token    = $_GET['token'] ?? null;

        if (!$selector || !$token) {
            http_response_code(400);
            echo "Missing selector or token";
            return;
        }

        // Redirect to the email verification page
        $this->redirect("/email-verify?selector={$selector}&token={$token}");
    }

    #[GET]
    #[Path('/email-verify')]
    public function emailVerify(): void
    {
        $selector = $_GET['selector'] ?? '';
        $token    = $_GET['token'] ?? '';

        echo $this->render('auth/email_verify.php', [
            'selector' => $selector,
            'token' => $token,
        ]);
    }
}
