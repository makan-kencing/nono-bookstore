<?php
declare(strict_types=1);

?>
<link rel="stylesheet" href="/static/styles/Account/forgot-password.css">

<div id="forgot-password-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Reset Password</h2>
        <form id="forgot-password-form">
            <input type="email" id="forgot-email" placeholder="Enter your email" required>
            <button type="submit">Send Reset Link</button>
        </form>
        <div id="forgot-password-message"></div>
    </div>
</div>

<script>
    $(document).ready(function() {
        const $modal = $('#forgot-password-modal');
        const $close = $modal.find('.close');

        // Open modal if URL hash is #forgot-password
        if (window.location.hash === '#forgot-password') {
            $modal.show();
        }

        // Open modal on click
        $('.forgot-password, .forgot-link').click(function(e) {
            e.preventDefault();
            $modal.show();
            history.pushState(null, '', '#forgot-password');
        });

        // Close modal
        $close.click(function() {
            $modal.hide();
            history.pushState(null, '', window.location.pathname);
        });

        // Submit form via AJAX
        $('#forgot-password-form').submit(function(e) {
            e.preventDefault();
            const email = $('#forgot-email').val();

            $.ajax({
                url: '/api/password-reset',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ email }),
                success: function(res) {
                    $('#forgot-password-message').text(res.message);
                },
                error: function() {
                    $('#forgot-password-message').text('Error sending reset link.');
                }
            });
        });
    });

</script>
