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
            <form id="loginForm" method="POST" action="/api/login">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-container">
                        <input type="password" id="password" name="password" required>
                        <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                    </div>
                </div>
                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        Remember me
                    </label>
                    <a href="#" class="forgot-password">Forgot password?</a>
                </div>
                <button type="submit" class="login-btn">Login</button>
            </form>
            <div class="signup-link">
                Don't have an account? <a href="/register">Sign up</a>
            </div>
        </div>
        <div class="right-section">
            <img src="/static/assets/login-illustration.jpg" alt="login-illustration">
        </div>
    </div>

    <script>
        $("form#loginForm").submit(/** @param {jQuery.Event} e */ (e) => {
            e.preventDefault();
            console.log(e);

            const data = new FormData(e.target);

            $.ajax(
                e.target.action,
                {
                    method: e.target.method,
                    contentType: "application/json",
                    dataType: "text",
                    data: JSON.stringify(Object.fromEntries(data.entries())),
                    error: (xhr, textStatus, errorThrown) => {
                        console.error(textStatus, errorThrown)
                    },
                    success: (data, textStatus) => {
                        console.log(data, textStatus);
                        window.location.href = "/";
                    }
                }
            )
        })

        // Password toggle functionality
        $("#togglePassword").on("click", function() {
            const password = $("#password");
            const type = password.attr("type") === "password" ? "text" : "password";
            password.attr("type", type);
            $(this).toggleClass("fa-eye fa-eye-slash");
        });
    </script>
<?php
$content = ob_get_clean();
include __DIR__ . "/_base.php";
