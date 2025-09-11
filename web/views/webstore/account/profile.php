<?php

declare(strict_types=1);

use App\Core\View;
use App\Entity\User\User;

assert(isset($user) && $user instanceof User);

ob_start();
?>
    <div class="profile-container">
        <h2>Profile</h2>

        <!-- Success / Error Messages -->
        <?php if (isset($_GET['success'])): ?>
            <div class="message success">Profile updated successfully!</div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="message error"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>

        <!-- Profile Form -->
        <form method="post" action="/user/updateProfile" enctype="multipart/form-data" id="profileForm">

            <!-- Avatar -->
            <div class="form-group">
                <label for="avatar">Avatar</label>
                <?php if ($user->image !== null): ?>
                    <img src="<?= htmlspecialchars($user->image->filepath) ?>" alt="<?= $user->image->alt ?>"
                         style="max-width: 120px; display:block; margin-bottom:10px;">
                <?php endif; ?>
                <input type="file" name="avatar" id="avatar" accept="image/*">
            </div>

            <!-- Username -->
            <div class="form-group">
                <label>Current Username</label>
                <label>
                    <input type="text" value="<?= htmlspecialchars($user->username) ?>" disabled>
                </label>
                <label for="username">New Username</label>
                <input type="text" name="username" id="username">
                <label for="confirm_username">Confirm New Username</label>
                <input type="text" name="confirm_username" id="confirm_username">
            </div>

            <!-- Email -->
            <div class="form-group">
                <label>Current Email</label>
                <label>
                    <input type="email" value="<?= htmlspecialchars($user->email) ?>" disabled>
                </label>
                <label for="email">New Email</label>
                <input type="email" name="email" id="email">
                <label for="confirm_email">Confirm New Email</label>
                <input type="email" name="confirm_email" id="confirm_email">
            </div>

            <!-- Contact Number -->
            <div class="form-group">
                <label for="contact_no">Contact Number</label>
                <input type="text" name="contact_no" id="contact_no"
                       value="<?= htmlspecialchars($user->contactNo ?? '') ?>">
            </div>

            <!-- Date of Birth -->
            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" name="dob" id="dob" value="<?= htmlspecialchars($user->profile->dob ?? '') ?>">
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input type="password" name="current_password" id="current_password">
                <label for="new_password">New Password</label>
                <input type="password" name="new_password" id="new_password">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" name="confirm_password" id="confirm_password">
            </div>

            <button type="submit">Save Changes</button>
        </form>
    </div>

    <script>
        $("#profileForm").on("submit", function (e) {
            e.preventDefault();

            const username = $("#username").val();
            const confirmUsername = $("#confirm_username").val();
            const email = $("#email").val();
            const confirmEmail = $("#confirm_email").val();
            const contactNo = $("#contact_no").val();
            const dob = $("#dob").val();

            // validations
            if (username && username !== confirmUsername) {
                alert("Usernames do not match!");
                return;
            }
            if (email && email !== confirmEmail) {
                alert("Emails do not match!");
                return;
            }

            const updateCalls = [];

            if (username || email) {
                updateCalls.push(
                    $.ajax({
                        url: "/api/user/updateUser",
                        type: "PUT",
                        contentType: "application/json",
                        data: JSON.stringify({
                            username: username || null,
                            email: email || null
                        })
                    })
                );
            }

            if (contactNo || dob) {
                updateCalls.push(
                    $.ajax({
                        url: "/api/user/updateUserProfile",
                        type: "PUT",
                        contentType: "application/json",
                        data: JSON.stringify({
                            contact_no: contactNo || null,
                            dob: dob || null
                        })
                    })
                );
            }

            // Run all updates
            $.when.apply($, updateCalls)
                .done(function () {
                    alert("Profile updated successfully!");
                    window.location.href = window.location.pathname + "?success=1";
                })
                .fail(function (xhr) {
                    const msg = xhr.responseText || "Failed to update profile.";
                    alert("Error: " + msg);
                    window.location.href = window.location.pathname + "?error=" + encodeURIComponent(msg);
                });
        });
    </script>
<?php

$title = 'User Profile';
$content = ob_get_clean();

echo View::render(
    '/webstore/_base.php',
    ['title' => $title, 'content' => $content]
);
