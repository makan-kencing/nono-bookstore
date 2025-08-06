<?php

declare(strict_types=1);

$title = 'profile';

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
                    <input type="text" id="username" name="username">

                    <label for="email">Email</label>
                    <input type="email" id="email" name="email">

                    <button type="submit" class="btn">Update</button>
                </form>

                <form class="form-group">
                    <label for="Contect No">Contact No</label>
                    <input type="tel" id="phone" name="phone">

                    <label for="birthday">Birthday</label>
                    <input type="date" id="birthday" name="birthday" value="1995-05-03">

                    <button type="submit" class="btn">Update</button>
                </form>

                <form class="form-group address-form">
                    <h3>Address Information</h3>

                    <label for="address1">Address 1</label>
                    <input type="text" id="address1" name="address1">

                    <label for="address2">Address 2</label>
                    <input type="text" id="address2" name="address2">

                    <label for="state">State</label>
                    <input type="text" id="state" name="state">

                    <div class="form-row">
                        <div>
                            <label for="postcode">Postcode</label>
                            <input type="text" id="postcode" name="postcode">
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


<?php
$content = ob_get_clean();

include __DIR__ . "/_base.php";


