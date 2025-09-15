<?php

declare(strict_types=1);

use App\Core\Template;
use App\Core\View;
use App\Entity\User\User;

assert(isset($user) && $user instanceof User);

$template = new Template(
    'webstore/_base.php',
    ['title' => 'Account']
);

$dobYear = $dobMonth = $dobDay = 0;
if ($user->profile?->dob instanceof DateTime) {
    $dobYear = (int)$user->profile->dob->format('Y');
    $dobMonth = (int)$user->profile->dob->format('m');
    $dobDay = (int)$user->profile->dob->format('d');
}

?>

<?php $template->startFragment('header'); ?>

<link rel="stylesheet" href="/static/styles/Account/profile.css">

<style>
    .validity {
        display: none;
        margin-left: 1rem;

        color: red !important;
    }

    input[data-taken='1'] + .validity {
        display: inline;
    }
</style>

<?php $template->endFragment(); ?>

<?php $template->start(); ?>

<div class="profile-container">
    <?= View::render('webstore/account/_sidebar.php', ['user' => $user, 'currentMenu' => 0]); ?>

    <section class="main-content">
        <div class="content-header">
            <h1>My Profile</h1>
            <p>Manage and protect your account</p>
        </div>

        <div class="profile-body">
            <form id="profile-form" class="profile-form">
                <div class="form-row">
                    <label for="username">Username</label>
                    <div class="form-field">
                        <input type="text" id="username" name="username" value="<?= $user->username ?? '' ?>">
                        <span class="validity">Username is taken</span>
                    </div>
                </div>

                <div class="form-row">
                    <label for="email">Email</label>
                    <div class="form-field">
                        <input type="email" id="email" name="email" value="<?= $user->email ?? '' ?>">
                        <span class="validity">Email is taken</span>
                    </div>
                </div>

                <div class="form-row">
                    <label for="contact">Contact Number</label>
                    <div class="form-field">
                        <input type="text" id="contact" name="contact_no" pattern="[0-9+\-\s]{7,20}"
                               value="<?= $user->profile->contactNo ?? '' ?>">
                    </div>
                </div>

                <div class="form-row">
                    <label>Date of birth</label>
                    <div class="form-field dob-selects">
                        <select name="day">
                            <option value="">Day</option>
                            <?php for ($day = 1; $day <= 31; $day++): ?>
                                <option
                                    value="<?= $day ?>" <?= $day === $dobDay ? 'selected' : '' ?>><?= $day ?></option>
                            <?php endfor; ?>
                        </select>
                        <select name="month">
                            <option value="">Month</option>
                            <?php $months = ["January", "February", "March", "April", "May", "June", "July",
                                "August", "September", "October", "November", "December"]; ?>
                            <?php foreach ($months as $month => $text): ?>
                                <option
                                    value="<?= $month + 1 ?>" <?= ($month + 1) === $dobMonth ? 'selected' : '' ?>><?= $text ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select name="year">
                            <option value="">Year</option>
                            <?php $thisYear = (int)(new DateTime())->format('Y') ?>
                            <?php for ($month = 0; $month < 100; $month++): ?>
                                <?php $year = $thisYear - $month ?>
                                <option
                                    value="<?= $year ?>" <?= $year === $dobYear ? 'selected' : '' ?>><?= $year ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <label></label>
                    <div class="form-field">
                        <button type="submit" class="save-btn">Save</button>
                    </div>
                </div>
            </form>

            <form class="image-upload" enctype="multipart/form-data">
                <img src="<?= $user->image->filepath ?? '' ?>" alt="Profile Image" class="qr-code">

                <input type="file" accept="image/png, image/jpeg, image/webp" name="profile_image" id="profile-image" hidden>

                <label for="profile-image" class="select-image-btn">Select Image</label>

                <p class="file-info">File size: maximum 5 MB</p>
                <p class="file-info">File extension: JPEG, PNG, WEBP</p>
            </form>
        </div>
    </section>
</div>

<script>
    $("input[name=username]").change(/** @param {jQuery.Event} e */ function (e) {
        if (this.value === this.defaultValue) {
            this.setCustomValidity("")
            this.dataset.taken = "0";
            return;
        }

        $.ajax(
            `/api/user/username/${this.value}`,
            {
                method: "GET",
                success: (data) => {
                    if (data.exists) {
                        this.setCustomValidity("Username is already taken.")
                        this.dataset.taken = "1";
                    }
                    else {
                        this.setCustomValidity("");
                        this.dataset.taken = "0";
                    }
                }
            }
        )
    });

    $("input[name=email]").change(/** @param {jQuery.Event} e */ function (e) {
        if (this.value === this.defaultValue) {
            this.setCustomValidity("");
            this.dataset.taken = "0";
            return;
        }

        $.ajax(
            `/api/user/email/${this.value}`,
            {
                method: "GET",
                success: (data) => {
                    if (data.exists) {
                        this.setCustomValidity("Email is already taken.")
                        this.dataset.taken = "1";
                    }
                    else {
                        this.setCustomValidity("");
                        this.dataset.taken = "0";
                    }
                }
            }
        )
    });

    $("form.image-upload input[type=file]").change(/** @param {jQuery.Event} e */ function (e) {
        const form = this.closest("form");

        const data = new FormData(form);

        $(form).find("label[for=profile-image]").text("Saving");

        $.ajax(
            "/api/user/profile/image",
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
                }
            }
        );
    })

    $('.profile-form').submit(/** @param {jQuery.Event} e */ function (e) {
        e.preventDefault();

        const data = new FormData(e.target);
        data.append('dob', `${data.get('year')}-${data.get('month')}-${data.get('day')}`);

        // Update user (username + email)
        $.ajax(
            "/api/user/update-profile/<?= $user->id ?>",
            {
                method: 'PUT',
                contentType: 'application/json',
                data: JSON.stringify(Object.fromEntries(data.entries())),
                success: () => {
                    alert("Profile updated successfully!");
                },
                error: (xhr) => {
                    switch (xhr.status) {
                        case 409:
                            if (xhr.responseText.includes("Email"))
                                $("input[name=email]")[0].setCustomValidity("Email is already taken.")
                            if (xhr.responseText.includes("Username"))
                                $("input[name=username]")[0].setCustomValidity("Username is already taken.");
                            break;
                        default:
                            alert("Profile update failed: " + xhr.responseText);
                    }
                }
            }
        );
    });
</script>

<?= $template->end() ?>
