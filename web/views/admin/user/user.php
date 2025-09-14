<?php

declare(strict_types=1);

use App\Core\View;
use App\Entity\User\User;
use App\Entity\User\UserRole;

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

                <form id="profileForm" class="form-group">
                    <div>
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" data-username-taken="0" value="<?= $user->username?>">
                        <span class="hint" style="color: red">Username taken</span>
                    </div>

                    <div>
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?= $user->email?>">
                    </div>

                    <div>
                        <label for="role">Role</label>
                        <select id="role" name="role">
                            <?php foreach (UserRole::cases() as $role): ?>
                                <option id="role" name="role" value="<?= $role->name ?>"
                                    <?= $role === $user->role ? 'selected' : ''?>>
                                    <?= $role->title() ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <button type="submit" class="btn">Update</button>
                    </div>
                </form>

                <style>
                    [data-username-taken='0'] + span.hint {
                        display: none;
                    }
                </style>

                <form id="profileFormContact" class="form-group" >
                    <div>
                        <label for="phone">Contact No</label>
                        <input type="tel" id="contact_no" name="contact_no" value="<?= $user->profile?->contactNo ?>">
                    </div>
                    <div>
                        <label for="birthday">Birthday</label>
                        <input type="date" id="dob" name="dob" value="<?=$user->profile?->dob?->format('Y-m-d') ?>">
                    </div>
                    <div>
                        <span>Status: <span><?= $user->isBlocked ? 'Blocked' : 'Normal' ?></span></span>
                        <button id="block-user" type="button"><?= $user->isBlocked ? 'Unblock' : 'Block' ?></button>
                    </div>
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

        $("form#profileForm").submit(/** @param {jQuery.Event} e */ (e) =>{
            e.preventDefault();
            console.log(e)

            const data =new FormData(e.target);

            $.ajax(
                "/api/user/<?= $user->id ?>",
                {
                    method:'PUT',
                    contentType:"application/json",
                    data:(JSON.stringify(Object.fromEntries(data.entries()))),
                    error:(jqXHR, textStatus, errorThrown)=>{
                        console.error(jqXHR, textStatus, errorThrown)
                        switch (jqXHR){
                            case 500:
                                alert("Error")
                        }
                    },
                    success:(data, textStatus, jqXHR)=>{
                        console.log(data, textStatus, jqXHR)
                        alert("Done update");
                        window.location.reload();
                    }
                }
            );
        });

        $("form#profileFormContact").submit(/** @param {jQuery.Event} e */ (e) =>{
            e.preventDefault();
            console.log(e)

            const data =new FormData(e.target);

            $.ajax(
                "/api/user/update-profile/<?= $user->id ?>",
                {
                    method:'PUT',
                    contentType:"application/json",
                    data:(JSON.stringify(Object.fromEntries(data.entries()))),
                    error:(jqXHR, textStatus, errorThrown)=>{
                        console.error(jqXHR, textStatus, errorThrown)
                        switch (jqXHR){
                            case 500:
                                alert("Error")
                        }
                    },
                    success:(data, textStatus, jqXHR)=>{
                        console.log(data, textStatus, jqXHR)
                        alert("Done update");
                        window.location.reload();
                    }
                }
            );
        });
    </script>

<?php

$title = 'User Details';
$content = ob_get_clean();

echo View::render(
    'admin/_base.php',
    ['title' => $title, 'content' => $content]
);

