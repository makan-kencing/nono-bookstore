<?php

declare(strict_types=1);

use App\Core\View;
use App\Entity\User\User;
use App\Entity\User\UserRole;
use App\Exception\UnauthorizedException;
use App\Router\AuthRule;
use App\Service\AuthService;

assert(isset($user) && $user instanceof User);

$context = AuthService::getLoginContext();
if ($context === null)
    throw new UnauthorizedException();

$hasPermission = AuthRule::HIGHER->check($context->role, $user->role);

ob_start();
?>
    <main>
        <div>
            <aside>
                <?= View::render('admin/user/_sidebar.php', ['currentMenu' => 'User Details', 'user' => $user]) ?>
            </aside>

            <section class="profile-card" style="width: 100%">
                <h2>Account Profile</h2>
                <form class="image-upload" enctype="multipart/form-data">
                    <div class="avatar-section" style="display: flex; justify-content: center;">
                        <img src="<?= $user->image?->filepath ?? '/static/assets/user.png' ?>"
                             alt="<?= $user->image?->alt ?? '' ?>"
                             style="height: 400px; border-radius: 100%; aspect-ratio: 1; object-fit: cover">
                    </div>

                    <?php if ($hasPermission): ?>
                        <div style="display: flex; flex-flow: column; align-items: center">
                            <input type="file" accept="image/png, image/jpeg, image/webp" name="profile_image"
                                   id="profile-image" hidden>

                            <label for="profile-image"
                                   style="background-color: #fff; border: 1px solid #dbdbdb; padding: 8px 15px; cursor: pointer; margin-bottom: 15px;">
                                Select Image
                            </label>

                            <p class="file-info">File size: maximum 5 MB</p>
                            <p class="file-info">File extension: JPEG, PNG, WEBP</p>
                        </div>
                    <?php endif; ?>
                </form>

                <div class="form-grid">

                    <form id="profileForm" class="form-group">
                        <div>
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" data-username-taken="0"
                                   value="<?= $user->username ?>">
                            <span class="hint" style="color: red">Username taken</span>
                        </div>

                        <div>
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="<?= $user->email ?>">
                        </div>

                        <div>
                            <label for="role">Role</label>
                            <select id="role" name="role">
                                <?php foreach (UserRole::cases() as $role): ?>
                                    <option id="role" name="role" value="<?= $role->name ?>"
                                        <?= $role === $user->role ? 'selected' : '' ?>>
                                        <?= $role->title() ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <?php if ($hasPermission): ?>
                            <div>
                                <button type="submit" class="btn">Update</button>
                            </div>
                        <?php endif; ?>
                    </form>

                    <style>
                        [data-username-taken='0'] + span.hint {
                            display: none;
                        }
                    </style>

                    <form id="profileFormContact" class="form-group">
                        <div>
                            <label for="phone">Contact No</label>
                            <input type="tel" id="contact_no" name="contact_no"
                                   value="<?= $user->profile?->contactNo ?>">
                        </div>
                        <div>
                            <label for="birthday">Birthday</label>
                            <input type="date" id="dob" name="dob"
                                   value="<?= $user->profile?->dob?->format('Y-m-d') ?>">
                        </div>
                        <div>
                            <span>Status: <span><?= $user->isBlocked ? 'Blocked' : 'Normal' ?></span></span>

                            <?php if ($hasPermission): ?>
                                <button id="block-user" type="button"><?= $user->isBlocked ? 'Unblock' : 'Block' ?></button>
                            <?php endif; ?>
                        </div>

                        <?php if ($hasPermission): ?>
                            <button type="submit" class="btn">Update</button>
                        <?php endif; ?>
                    </form>
                </div>
            </section>
        </div>
    </main>

    <script>
        $("button#block-user").click(/** @param {jQuery.Event} e */(e) => {
            const button = e.currentTarget;
            const isBlocked = button.innerText === "Unblock";
            console.log("isBlocked:", isBlocked);

            $.ajax(
                "/api/user/<?= $user->id ?>/block/toggle",
                {
                    method: "PUT",
                    error: (jqXHR, textStatus, errorThrown) => {
                        console.error(jqXHR, textStatus, errorThrown);

                        switch (jqXHR.status) {
                            case 401:
                                alert("You are not logged. ");
                                break;
                            case 403:
                                alert("You do not have permission to block this user.");
                                break;
                            case 409:
                                alert("You cannot block the user as it is referenced in other places.");
                                break;
                            default:
                                alert("Block failed.");
                        }
                    },
                    success: () => {
                        alert("User blocked");
                        window.location.reload();
                    }
                }
            );
        });

        $("input#username").change(/** @param {jQuery.Event} e */(e) => {
            console.log(e);

            $.get(
                "/api/user/username/" + e.target.value,
                (data) => {
                    if (data.exists) {
                        e.target.setCustomValidity("Username is taken.");
                        e.target.dataset.usernameTaken = "1";
                    } else {
                        e.target.setCustomValidity("");
                        e.target.dataset.usernameTaken = "0";
                    }
                }
            )
        });

        $("form#profileForm").submit(/** @param {jQuery.Event} e */(e) => {
            e.preventDefault();
            console.log(e)

            const data = new FormData(e.target);

            $.ajax(
                "/api/user/<?= $user->id ?>",
                {
                    method: 'PUT',
                    contentType: "application/json",
                    data: (JSON.stringify(Object.fromEntries(data.entries()))),
                    error: (jqXHR, textStatus, errorThrown) => {
                        console.error(jqXHR, textStatus, errorThrown)
                        switch (jqXHR) {
                            case 500:
                                alert("Error")
                        }
                    },
                    success: (data, textStatus, jqXHR) => {
                        console.log(data, textStatus, jqXHR)
                        alert("Done update");
                        window.location.reload();
                    }
                }
            );
        });

        $("form#profileFormContact").submit(/** @param {jQuery.Event} e */(e) => {
            e.preventDefault();
            console.log(e)

            const data = new FormData(e.target);

            $.ajax(
                "/api/user/update-profile/<?= $user->id ?>",
                {
                    method: 'PUT',
                    contentType: "application/json",
                    data: (JSON.stringify(Object.fromEntries(data.entries()))),
                    error: (jqXHR, textStatus, errorThrown) => {
                        console.error(jqXHR, textStatus, errorThrown)
                        switch (jqXHR) {
                            case 500:
                                alert("Error")
                        }
                    },
                    success: (data, textStatus, jqXHR) => {
                        console.log(data, textStatus, jqXHR)
                        alert("Done update");
                        window.location.reload();
                    }
                }
            );
        });

        $("form.image-upload input[type=file]").change(/** @param {jQuery.Event} e */ function (e) {
            const form = this.closest("form");

            const data = new FormData(form);

            $(form).find("label[for=profile-image]").text("Saving");

            $.ajax(
                "/api/user/<?= $user->id ?>/profile/image",
                {
                    method: "POST",
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: (data) => {
                        $(form).find("img")
                            .attr("src", data.filepath)
                            .attr("alt", data.alt);
                        $(form).find("label[for=profile-image]")
                            .text("Saved");
                    },
                    error: (xhr) => {
                        console.error(xhr);
                        switch (xhr.status) {
                            case 403:
                                alert("You do not have permission to set this user profile image.");
                                break;
                            case 413:
                                alert("The file image is too large.");
                                break;
                        }
                    }
                }
            );
        })
    </script>

<?php

$title = 'User Details';
$content = ob_get_clean();

echo View::render(
    'admin/_base.php',
    ['title' => $title, 'content' => $content]
);

