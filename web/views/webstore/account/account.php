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

// extract DOB
$dobDay = $dobMonth = $dobYear = '';

if ($user->profile?->dob instanceof DateTime) {
    $dobYear = $user->profile->dob->format('Y');
    $dobMonth = $user->profile->dob->format('m');
    $dobDay = $user->profile->dob->format('d');
}

?>

<?php $template->start(); ?>
<link rel="stylesheet" href="/static/styles/Account/profile.css">

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
                    <label>Username</label>
                    <div class="form-field">
                        <input type="text" id="username" value="<?= $user->username ?? '' ?>">
                    </div>
                </div>

                <div class="form-row">
                    <label for="email">Email</label>
                    <div class="form-field">
                        <input type="email" id="email" value="<?= $user->email ?? '' ?>">
                    </div>
                </div>

                <div class="form-row">
                    <label for="contact">Contact Number</label>
                    <div class="form-field">
                        <input type="text" id="contact" value="<?= $user->profile->contactNo ?? '' ?>">
                    </div>
                </div>

                <div class="form-row">
                    <label>Date of birth</label>
                    <div class="form-field dob-selects">
                        <select id="dob-day" data-selected="<?= $dobDay ?>"></select>
                        <select id="dob-month" data-selected="<?= $dobMonth ?>"></select>
                        <select id="dob-year" data-selected="<?= $dobYear ?>"></select>
                    </div>
                </div>

                <div class="form-row">
                    <label></label>
                    <div class="form-field">
                        <button type="submit" class="save-btn">Save</button>
                    </div>
                </div>
            </form>

            <div class="image-upload">
                <img src="<?= $user->image->filepath ?? '' ?>" alt="Profile Image" class="qr-code">
                <button type="button" class="select-image-btn">Select Image</button>
                <p class="file-info">File size: maximum 1 MB</p>
                <p class="file-info">File extension: JPEG, PNG</p>
            </div>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const daySelect = document.getElementById('dob-day');
        const monthSelect = document.getElementById('dob-month');
        const yearSelect = document.getElementById('dob-year');

        const selectedDay = daySelect.dataset.selected;
        const selectedMonth = monthSelect.dataset.selected;
        const selectedYear = yearSelect.dataset.selected;

        // Day
        daySelect.innerHTML = '<option value="">Day</option>';
        for (let i = 1; i <= 31; i++) {
            daySelect.innerHTML += `<option value="${i}" ${i == selectedDay ? 'selected' : ''}>${i}</option>`;
        }

        // Month
        const months = ["January", "February", "March", "April", "May", "June", "July",
            "August", "September", "October", "November", "December"];
        monthSelect.innerHTML = '<option value="">Month</option>';
        months.forEach((month, index) => {
            const value = index + 1;
            monthSelect.innerHTML += `<option value="${value}" ${value == selectedMonth ? 'selected' : ''}>${month}</option>`;
        });

        // Year
        const currentYear = new Date().getFullYear();
        yearSelect.innerHTML = '<option value="">Year</option>';
        for (let i = currentYear; i >= currentYear - 100; i--) {
            yearSelect.innerHTML += `<option value="${i}" ${i == selectedYear ? 'selected' : ''}>${i}</option>`;
        }
    });

    $(document).ready(function () {
        const userId = <?= $user->id ?>;

        $('.profile-form').on('submit', function (e) {
            e.preventDefault();

            const username = $('#username').val().trim();
            const email = $('#email').val().trim();
            const contactNo = $('#contact').val().trim();

            const day = $('#dob-day').val();
            const month = $('#dob-month').val();
            const year = $('#dob-year').val();
            const dob = (day && month && year) ? `${year}-${month}-${day}` : null;

            // ✅ Frontend validation for contact number
            const contactRegex = /^[0-9+\-\s]{7,20}$/;
            if (contactNo && !contactRegex.test(contactNo)) {
                alert("❌ Invalid contact number format.\n\nUse only digits, +, - and spaces. Must be 7–20 characters.");
                return;
            }

            // Update user (username + email)
            $.ajax({
                url: `/api/user/${userId}`,
                type: 'PUT',
                contentType: 'application/json',
                data: JSON.stringify({
                    username: username,
                    email: email
                }),
                success: function (response) {
                    console.log("User update success:", response);
                },
                error: function (xhr) {
                    console.error("User update failed:", xhr.responseText);
                }
            });

            // Update user profile (contactNo + dob)
            $.ajax({
                url: `/api/user/update-profile/${userId}`,
                type: 'PUT',
                contentType: 'application/json',
                data: JSON.stringify({
                    contact_no: contactNo,
                    dob: dob
                }),
                success: function (response) {
                    console.log("Profile update success:", response);
                    alert("✅ Profile updated successfully!");
                },
                error: function (xhr) {
                    console.error("Profile update failed:", xhr.responseText);
                    alert("❌ Profile update failed: " + xhr.responseText);
                }
            });
        });
    });
</script>

<?= $template->end() ?>
