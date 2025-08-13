<?php

declare(strict_types=1);

$title = 'Register';
ob_start();
?>
    <link rel="stylesheet" href="/static/styles/Auth/register.css"/>
    <div class="register-container">
        <div class="left-section">
            <img src="/static/assets/register-illustration.jpeg" alt="registration-illustration"/>
        </div>
        <div class="right-section">
            <h2>Register An Account</h2>
            <form class="form-container" id="registration" method="POST" action="/api/user/register">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" data-username-taken="0" required>
                    <span class="hint">Username is taken</span>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" data-email-taken="0" required>
                    <span class="hint">Email is taken</span>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm-password">Confirm Password</label>
                    <input type="password" id="confirm-password" name="confirm-password" data-match="0" required>
                    <span class="hint">Passwords do not match</span>
                </div>
                <div class="terms-policy">
                    By signing up, you agree to Novelty N'Nonsense <a href="#">Terms</a> and <a href="#">Privacy
                        Policies</a>
                </div>
                <button type="submit">Register</button>
                <div class="return-login">Have an account? <a href="/login">Login</a></div>
            </form>
        </div>
    </div>

    <script>
        $("input#username").change(/** @param {jQuery.Event} e */(e) => {
            $.get(
                "/api/user/username/" + e.target.value,
                (data, textStatus, jqXHR) => {
                    console.log(data, textStatus, jqXHR);

                    if (data.exists)
                        e.target.dataset.usernameTaken = "1";
                    else
                        e.target.dataset.usernameTaken = "0";

                },
                "json"
            )
        });

        $("input#email").change(/** @param {jQuery.Event} e */(e) => {
            $.get(
                "/api/user/email/" + e.target.value,
                (data, textStatus, jqXHR) => {
                    console.log(data, textStatus, jqXHR);

                    if (data.exists)
                        e.target.dataset.emailTaken = "1";
                    else
                        e.target.dataset.emailTaken = "0";

                },
                "json"
            )
        });

        $(function () {
            const $password = $("#password");
            const $confirm = $("#confirm-password");

            function validateMatch() {
                const pw = $password.val();
                const cf = $confirm.val();
                const mismatch = cf.length > 0 && pw !== cf;

                $confirm.attr("data-match", mismatch ? "1" : "0");
            }

            $password.on("input", validateMatch);
            $confirm.on("input", validateMatch);
        });

        /* TODO: When error exist disable the register button */

        $("form#registration").submit(/** @param {jQuery.Event} e */(e) => {
            e.preventDefault();
            console.log(e);

            const data = new FormData(e.target);

            $.ajax(
                e.target.action,
                {
                    method: e.target.method,
                    contentType: "application/json",
                    data: JSON.stringify(Object.fromEntries(data.entries())),
                    error: (jqXHR, textStatus, errorThrown) => {
                        console.error(jqXHR, textStatus, errorThrown)
                    },
                    success: (data, textStatus, jqXHR) => {
                        console.log(data, textStatus, jqXHR);

                        // TODO: notify registration successful

                        // redirect to log in after 3 secs
                        setTimeout(() => {
                            window.location.href = "/login";
                        }, 3000);
                    }
                }
            );
        })
    </script>
<?php
$content = ob_get_clean();
include __DIR__ . "/_base.php";
