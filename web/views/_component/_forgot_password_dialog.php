<?php
declare(strict_types=1);

?>
<link rel="stylesheet" href="/static/styles/Account/forgot-password.css">

<dialog id="forgot-password-modal">
    <div class="modal-header">
        <div class="icon-container">
            <i class="fa-regular fa-envelope"></i>
        </div>
        <div class="title-container">
            <h2>Forgot Password</h2>
        </div>
        <button type="button" class="close-btn" aria-label="Close">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <div class="modal-body">
        <p>Enter your email address and we'll send you a link to reset your password.</p>
        <form id="forgot-password-form">
            <div class="form-group">
                <label for="forgot-email">Email address</label>
                <input type="email" id="forgot-email" name="email" placeholder="Enter your email" required>
            </div>
            <div id="forgot-password-message"></div>
            <div class="modal-actions">
                <button type="button" class="cancel-btn">Cancel</button>
                <button type="submit" class="submit-btn">Send Reset Link</button>
            </div>
        </form>
    </div>
</dialog>

<script>
    const modal = document.getElementById('forgot-password-modal');
    $('.close-btn, .cancel-btn').click(function () {
        modal.close();
    });

    $('#forgot-password-form').submit(/** @param {jQuery.Event} e */ function (e) {
        e.preventDefault();

        const data = new FormData(e.target);
        const submitButton = $(this).find('.submit-btn');
        const messageDiv = $('#forgot-password-message');

        // Show loading state
        submitButton.text('Sending...').prop('disabled', true);
        messageDiv.text('').removeClass('success error');

        $.ajax({
            url: '/api/password-reset',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(Object.fromEntries(data.entries())),
            success: function (res) {
                messageDiv.text(res.message || 'Reset link sent successfully!').addClass('success');
                setTimeout(() => modal.close(), 5000);
            },
            error: function () {
                messageDiv.text('Error sending reset link. Please try again.').addClass('error');
            },
            complete: function () {
                // Restore button state
                submitButton.text('Send Reset Link').prop('disabled', false);
            }
        });
    });
</script>
