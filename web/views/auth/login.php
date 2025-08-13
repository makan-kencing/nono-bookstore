<?php

declare(strict_types=1);

$title = 'Login';
ob_start();
?>
<link rel="stylesheet" href="/static/styles/Auth/login.css">
    <div class="left-section">
        <div class="login-form">
            <h2>Login</h2>
            <p class="subtitle">Login to access your account</p>
            <form id="loginForm">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-container">
                        <input type="password" id="password" required>
                        <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                    </div>
                </div>
                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" id="remember">
                        Remember me
                    </label>
                    <a href="#" class="forgot-password">Forgot password?</a>
                </div>
                <button type="submit" class="login-btn">Login</button>
            </form>
            <div class="signup-link">
                Don't have an account? <a href="#">Sign up</a>
            </div>
        </div>
        <div class="right-section">
            <img src="/static/assets/login-illustration.jpg" alt="login-illustration">
        </div>
    </div>
<?php
$content = ob_get_clean();
include __DIR__ . "/_base.php";
