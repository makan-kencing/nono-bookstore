<?php

declare(strict_types=1);

use App\Core\Template;
use App\Core\View;
use App\Entity\User\User;

$title = 'Update Password';
ob_start();

assert(isset($user) && $user instanceof User);

$template = new Template(
    'webstore/_base.php',
    ['title' => 'Update Password']
);

?>
<?php $template->start(); ?>

<link rel="stylesheet" href="/static/styles/Account/updatePassword.css"/>
<link rel="stylesheet" href="/static/styles/Account/profile.css">

<div class="profile-container">
    <?= View::render('webstore/account/_sidebar.php', ['user' => $user, 'currentMenu' => 2]); ?>

    <div class="password-form">
        <h2>Update Password</h2>
        <form id="passwordForm">
            <div class="form-group">
                <label for="old_password">Old Password</label>
                <input type="password" id="old_password" name="old_password" required>
                <i class="fas fa-eye toggle-password" data-target="old_password"></i>
            </div>

            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" required>
                <i class="fas fa-eye toggle-password" data-target="new_password"></i>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
                <i class="fas fa-eye toggle-password" data-target="confirm_password"></i>
            </div>

            <button type="submit">Update</button>
        </form>

        <a href="/forgot-password" class="forgot-link">Forgot password?</a>
        <div id="responseMessage" class="message"></div>
    </div>
</div>

<script>
    // Password toggle function
    $(".toggle-password").on("click", function () {
        const targetId = $(this).data("target");
        const $input = $("#" + targetId);
        const type = $input.attr("type") === "password" ? "text" : "password";
        $input.attr("type", type);
        $(this).toggleClass("fa-eye fa-eye-slash");
    });

    $(document).ready(function () {
        const userId = <?= $user->id ?>;

        $('#passwordForm').on('submit', function (e) {
            e.preventDefault();

            const oldPassword = $('#old_password').val();
            const newPassword = $('#new_password').val();
            const confirmPassword = $('#confirm_password').val();

            if (newPassword !== confirmPassword) {
                $('#responseMessage').text("New password and confirm password do not match.")
                    .addClass("error").removeClass("success");
                return;
            }

            $.ajax({
                url: `/api/user/update-password/${userId}`,
                type: "PUT",
                contentType: "application/json",
                data: JSON.stringify({
                    old_password: oldPassword,
                    new_password: newPassword,
                    confirm_password: confirmPassword
                }),
                xhrFields: {withCredentials: true},
                success: function (res) {
                    $('#responseMessage').text(res.message)
                        .addClass("success").removeClass("error");
                    $('#passwordForm')[0].reset();
                },
                error: function (xhr) {
                    let msg = "Password update failed.";
                    if (xhr.responseJSON) {
                        msg = JSON.stringify(xhr.responseJSON);
                    }
                    $('#responseMessage').text(msg)
                        .addClass("error").removeClass("success");
                }
            });
        });
    });
</script>
<?= $template->end() ?>

