<?php

declare(strict_types=1);

use App\Core\View;
use App\Entity\User\User;

assert(isset($user) && $user instanceof User);

ob_start();
?>
    <div style="display: flex; flex-flow: row; ">
        <div>
            <?= View::render('admin/user/_sidebar.php', ['currentMenu' => 'User Details', 'user' => $user]) ?>
        </div>

        <div class="profile-card" style="width: 100%">
            <h2>Account Profile</h2>

            <div class="avatar-section">
                <i class="fa-regular fa-circle-user"></i>
            </div>

            <div class="form-grid">

                <form class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" data-username-taken="0" value="<?= $user->username?>">
                    <span class="hint" style="color: red">Username taken</span>

                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= $user->email?>">
                    <label><?= $user->role->title() ?></label>
                    <button type="submit" class="btn">Update</button>
                </form>

                <style>
                    [data-username-taken='0'] + span.hint {
                        display: none;
                    }
                </style>

                <form class="form-group">
                    <label for="phone">Contact No</label>
                    <input type="tel" id="phone" name="phone" value="<?= $user->profile?->contactNo ?>">

                    <label for="birthday">Birthday</label>
                    <input type="date" id="birthday" name="birthday" value="<?= $user->profile?->dob ?>">

                    <button type="submit" class="btn">Update</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        $("input#username").change(/** @param {jQuery.Event} e */ (e) => {
            console.log(e);

            $.get(
                "/api/user/username/" + e.target.value,
                (data) => {
                    if (data.exists) {
                        e.target.setCustomValidity("Username is taken.");
                        e.target.dataset.usernameTaken = "1";
                    }
                    else {
                        e.target.setCustomValidity("");
                        e.target.dataset.usernameTaken = "0";
                    }
                }
            )
        })
    </script>

<?php

$title = 'User Details';
$content = ob_get_clean();

echo View::render(
    'admin/_base.php',
    ['title' => $title, 'content' => $content]
);

