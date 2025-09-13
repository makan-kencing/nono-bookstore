<?php
declare(strict_types=1);

use App\Core\Template;

$template = new Template(
    'webstore/_base.php',
    ['title' => 'Reset Password']
);

?>
<?php $template->start(); ?>
<link rel="stylesheet" href="/static/styles/Account/reset-password.css">

<div class="reset-container">
    <h2>Reset Password</h2>
    <div id="reset-content"></div>
</div>
<script>
    $(document).ready(function () {
        const container = $('#reset-content');

        // Get selector and token from URL (entirely in JS)
        const urlParams = new URLSearchParams(window.location.search);
        const selector = urlParams.get('selector');
        const token = urlParams.get('token');

        if (!selector || !token) {
            container.html('<p class="error">Invalid reset link</p>');
            return;
        }

        // Render form dynamically
        container.html(`
        <form id="reset-password-form">
            <input type="password" id="new-password" placeholder="New Password" required>
            <input type="password" id="confirm-password" placeholder="Confirm Password" required>
            <input type="hidden" id="selector" value="${selector}">
            <input type="hidden" id="token" value="${token}">
            <button type="submit">Reset Password</button>
        </form>
        <div id="reset-password-message" style="color:red; margin-top:10px;"></div>
    `);

        // Form submission handler
        $('#reset-password-form').submit(function (e) {
            e.preventDefault();

            const newPassword = $('#new-password').val();
            const confirmPassword = $('#confirm-password').val();

            if (newPassword !== confirmPassword) {
                $('#reset-password-message').text("Passwords do not match");
                return;
            }

            $(this).find('button').prop('disabled', true);

            $.ajax({
                url: '/api/password-resets/' + selector,
                type: 'PUT',
                contentType: 'application/json',
                data: JSON.stringify({ token: token, new_password: newPassword }),
                success: function (res) {
                    container.html(`<p style="color:green;">${res.message || "Password reset successfully"}</p>
                                <p><a href="/login">Go to login</a></p>`);
                },
                error: function (err) {
                    let msg = "Error resetting password";
                    if (err.responseJSON && err.responseJSON.message) {
                        msg = err.responseJSON.message;
                    }
                    $('#reset-password-message').text(msg);
                    $('#reset-password-form button').prop('disabled', false);
                }
            });
        });
    });
</script>
<?= $template->end() ?>
