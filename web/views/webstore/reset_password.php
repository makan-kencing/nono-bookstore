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
    <p class="reset-description">Enter your new password below. Ensure it meets all requirements.</p>
    <?php
    // Extract selector and token from URL
    $selector = $_GET['selector'] ?? '';
    $token = $_GET['token'] ?? '';
    if (!$selector || !$token): ?>
        <p class="error" style="color:red;">Invalid reset link. Please request a new one.</p>
    <?php else: ?>
        <form id="reset-password-form">
            <!-- New Password -->
            <div class="form-group password-wrapper">
                <label for="new-password">New Password</label>
                <input type="password" id="new-password" name="new-password" placeholder="Enter new password" required>
                <i class="fa-solid fa-eye toggle-password" data-target="new-password"></i>
            </div>

            <!-- Password Criteria Dropdown -->
            <ul id="password-criteria">
                <li id="req-length" class="invalid"><i class="fa-solid fa-xmark"></i>At least 8 characters</li>
                <li id="req-uppercase" class="invalid"><i class="fa-solid fa-xmark"></i>One uppercase letter</li>
                <li id="req-lowercase" class="invalid"><i class="fa-solid fa-xmark"></i>One lowercase letter</li>
                <li id="req-number" class="invalid"><i class="fa-solid fa-xmark"></i>One number</li>
                <li id="req-special" class="invalid"><i class="fa-solid fa-xmark"></i>One special character</li>
            </ul>

            <!-- Confirm Password -->
            <div class="form-group password-wrapper">
                <label for="confirm-password">Confirm New Password</label>
                <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm your password"
                       required>
                <i class="fa-solid fa-eye toggle-password" data-target="confirm-password"></i>
            </div>

            <div id="reset-password-message"></div>

            <!-- Hidden inputs for selector and token -->
            <input type="hidden" id="selector" value="<?= htmlspecialchars($selector) ?>">
            <input type="hidden" id="token" value="<?= htmlspecialchars($token) ?>">

            <button type="submit">Reset Password</button>
        </form>
    <?php endif; ?>
</div>

<script>
    $(document).ready(function () {
        const newPasswordInput = $('#new-password');
        const confirmPasswordInput = $('#confirm-password');
        const criteriaList = $('#password-criteria');
        const messageDiv = $('#reset-password-message');
        const form = $('#reset-password-form');
        const submitButton = form.find('button[type="submit"]');

        const requirements = {
            length: {el: $('#req-length'), regex: /.{8,}/},
            uppercase: {el: $('#req-uppercase'), regex: /[A-Z]/},
            lowercase: {el: $('#req-lowercase'), regex: /[a-z]/},
            number: {el: $('#req-number'), regex: /[0-9]/},
            special: {el: $('#req-special'), regex: /[^A-Za-z0-9]/}
        };

        newPasswordInput.on('focus input', function () {
            criteriaList.slideDown('fast');
        });

        newPasswordInput.on('blur', function () {
            if ($(this).val() === '') criteriaList.slideUp('fast');
        });

        // Real-time password validation
        newPasswordInput.on('input', function () {
            const pwd = $(this).val();
            for (const key in requirements) {
                const req = requirements[key];
                if (req.regex.test(pwd)) {
                    req.el.removeClass('invalid').addClass('valid');
                    req.el.find('i').removeClass('fa-xmark').addClass('fa-check');
                } else {
                    req.el.removeClass('valid').addClass('invalid');
                    req.el.find('i').removeClass('fa-check').addClass('fa-xmark');
                }
            }
        });

        confirmPasswordInput.on('input', function () {
            if ($(this).val() !== newPasswordInput.val()) {
                messageDiv.text('Passwords do not match');
            } else {
                messageDiv.text('');
            }
        });

        $('.toggle-password').on('click', function () {
            const targetId = $(this).data('target');
            const $input = $('#' + targetId);
            const type = $input.attr('type') === 'password' ? 'text' : 'password';
            $input.attr('type', type);
            $(this).toggleClass('fa-eye fa-eye-slash');
        });

        form.submit(function (e) {
            e.preventDefault();
            const newPassword = newPasswordInput.val();
            const confirmPassword = confirmPasswordInput.val();

            if (newPassword !== confirmPassword) {
                messageDiv.text('Passwords do not match');
                return;
            }

            let allValid = true;
            for (const key in requirements) {
                if (!requirements[key].regex.test(newPassword)) {
                    allValid = false;
                    break;
                }
            }
            if (!allValid) {
                messageDiv.text('Password does not meet all requirements.');
                return;
            }

            messageDiv.text('');
            submitButton.prop('disabled', true).text('Resetting...');

            const selector = $('#selector').val();
            const token = $('#token').val();

            $.ajax({
                url: '/api/password-reset/' + selector,
                type: 'PUT',
                contentType: 'application/json',
                data: JSON.stringify({token: token, new_password: newPassword}),
                success: function (res) {
                    $('.reset-container').html(`
                    <div class="reset-success">
                        <h2 style="color:#10b981;">Success!</h2>
                        <p>${res.message || 'Your password has been reset successfully.'}</p>
                        <a href="/" style="color:#4f46e5; text-decoration:none;">Go to Home</a>
                    </div>
                `);
                },
                error: function (err) {
                    let msg = 'Error resetting password. The link may have expired.';
                    if (err.responseJSON && err.responseJSON.message) {
                        msg = err.responseJSON.message;
                    }
                    messageDiv.text(msg);
                    submitButton.prop('disabled', false).text('Reset Password');
                }
            });
        });
    });

</script>
<?= $template->end() ?>
