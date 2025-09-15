<?php

declare(strict_types=1);

$title = 'Verify Email';

assert(isset($selector) && is_string($selector));
assert(isset($token) && is_string($token));

ob_start();
?>
    <div class="verify-container">
        <h2>Verify Your Email</h2>
        <p>Enter the OTP sent to your email to complete registration.</p>

        <form id="verify-email-form">
            <!-- Hidden inputs for selector + token -->
            <input type="hidden" id="selector" name="selector" value="<?= htmlspecialchars($selector) ?>"/>
            <input type="hidden" id="token" name="token" value="<?= htmlspecialchars($token) ?>"/>

            <div class="form-group">
                <label for="otp">OTP</label>
                <input type="text" id="otp" name="otp" required/>
            </div>

            <button type="submit">Verify Email</button>
        </form>

        <div id="verify-msg"></div>
    </div>

    <script>
        $(function () {
            const $form = $("#verify-email-form");
            const $msg = $("#verify-msg");

            $form.submit(function (e) {
                e.preventDefault();

                const payload = {
                    selector: $("#selector").val(),
                    token: $("#token").val(),
                    otp: $("#otp").val()
                };

                console.log("Posting to API:", payload);

                $.ajax("/api/email-verify", {
                    method: "POST",
                    contentType: "application/json",
                    dataType: "json",
                    data: JSON.stringify(payload),
                    success: () => {
                        $msg.text("Email verified! Redirecting to login...");
                        setTimeout(() => {
                            window.location.href = "/login";
                        }, 2000);
                    },
                    error: (jqXHR) => {
                        let errorMsg = "Verification failed";
                        try {
                            const resp = JSON.parse(jqXHR.responseText);
                            errorMsg += ": " + (resp.message ?? JSON.stringify(resp));
                        } catch {
                            errorMsg += ": " + jqXHR.statusText;
                        }
                        $msg.text(errorMsg);
                    }
                });
            });
        });
    </script>

<?php
$content = ob_get_clean();
include __DIR__ . "/_base.php";
