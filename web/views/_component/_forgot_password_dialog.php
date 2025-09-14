<?php
declare(strict_types=1);

?>
<link rel="stylesheet" href="/static/styles/Account/forgot-password.css">

<dialog id="forgot-password-modal">
    <form id="forgot-password-form">
        <div style="display: flex;">

            <h2>Reset Password</h2>
            <button type="reset" style="margin-left: auto" class="close">&times;</button>
        </div>
        <div>
            <label for="forgot-email"></label>
            <input type="email" id="forgot-email" name="email" placeholder="Enter your email" required>
        </div>
        <button type="submit">Send Reset Link</button>
        <div id="forgot-password-message"></div>
    </form>
</dialog>

<script>

    $('#forgot-password-form').submit(/** @param {jQuery.Event} e */ function (e) {
        e.preventDefault();

        const data = new FormData(e.target);

        $.ajax(
            '/api/password-reset',
            {
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(Object.fromEntries(data.entries())),
                success: function (res) {
                    $('#forgot-password-message').text(res.message);
                },
                error: function () {
                    $('#forgot-password-message').text('Error sending reset link.');
                }
            });
    });
</script>
