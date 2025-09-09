<?php

declare(strict_types=1);

use App\Entity\User\User;

$title = 'profile';
/** @var User $user */
assert(isset($user) && $user instanceof User );

ob_start();
?>
    <div class="profile-container">
        <div class="profile-card">
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
                    <input type="tel" id="phone" name="phone" value="<?= $user->profile->contactNo ?>">

                    <label for="birthday">Birthday</label>
                    <input type="date" id="birthday" name="birthday" value="<?= $user->profile->dob ?>">

                    <button type="submit" class="btn">Update</button>
                </form>

                <form class="form-group address-form">
                    <h3>Address Information</h3>

                    <label for="address1">Address 1</label>
                    <input type="text" id="address1" name="address1" value="<?=$user->profile->address1?>">

                    <label for="address2">Address 2</label>
                    <input type="text" id="address2" name="address2" value="<?=$user->profile->address2?>">

                    <label for="state">State</label>
                    <input type="text" id="state" name="state" value="<?=$user->profile->state?>">

                    <div class="form-row">
                        <div>
                            <label for="postcode">Postcode</label>
                            <input type="text" id="postcode" name="postcode" >
                        </div>
                        <div>
                            <label for="country">Country</label>
                            <input type="text" id="country" name="country">
                        </div>
                    </div>

                    <button type="submit" class="btn">Save Address</button>
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
$content = ob_get_clean();

include __DIR__ . "/_base.php";


