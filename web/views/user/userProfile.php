<?php

declare(strict_types=1);

$title = 'User Profile';
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
                <?php if (!empty($user['avatar'])): ?>
                    <img src="<?= htmlspecialchars($user['avatar']) ?>" alt="Avatar"
                         style="max-width: 120px; display:block; margin-bottom:10px;">
                <?php endif; ?>
                <input type="file" name="avatar" id="avatar" accept="image/*">
            </div>

            <!-- Username -->
            <div class="form-group">
                <label>Current Username</label>
                <label>
                    <input type="text" value="<?= htmlspecialchars($user['username']) ?>" disabled>
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
                    <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
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
                       value="<?= htmlspecialchars($user['contact_no'] ?? '') ?>">
            </div>

            <!-- Date of Birth -->
            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" name="dob" id="dob" value="<?= htmlspecialchars($user['dob'] ?? '') ?>">
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
        document.getElementById("profileForm").addEventListener("submit", function (e) {
            const username = document.getElementById("username").value;
            const confirmUsername = document.getElementById("confirm_username").value;
            if (username && username !== confirmUsername) {
                alert("Usernames do not match!");
                e.preventDefault();
            }

            const email = document.getElementById("email").value;
            const confirmEmail = document.getElementById("confirm_email").value;
            if (email && email !== confirmEmail) {
                alert("Emails do not match!");
                e.preventDefault();
            }

            const newPassword = document.getElementById("new_password").value;
            const confirmPassword = document.getElementById("confirm_password").value;
            if (newPassword && newPassword !== confirmPassword) {
                alert("Passwords do not match!");
                e.preventDefault();
            }
        });
    </script>
<?php
$content = ob_get_clean();

include __DIR__ . "/../webstore/_base.php";
