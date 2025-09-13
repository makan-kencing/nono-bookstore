<?php

declare(strict_types=1);

use App\Entity\User\User;

assert(isset($user) && $user instanceof User);
assert(isset($currentMenu) && is_int($currentMenu));

?>
<link rel="stylesheet" href="/static/styles/Account/sidebar.css">
<aside class="sidebar">
    <div class="user-info">
        <div class="avatar"><img src="/static/assets/default-user.jpg" alt="User Avatar"></div>
        <div class="user-details">
            <strong><?= $user->username ?? '' ?></strong>
            <a href="/account/profile"><i class="fas fa-pencil-alt"></i> Edit Profile</a>
        </div>
    </div>
    <nav class="sidebar-nav">
        <ul>
            <li><a href="/account/profile" class="active"><i class="far fa-user"></i> My Account</a>
                <ul class="submenu">
                    <li><a href="/account" class="<?= $currentMenu === 0 ? 'active-link' : '' ?>">Profile</a></li>
                    <li><a href="/account/addresses" class="<?= $currentMenu === 1 ? 'active-link' : '' ?>">Addresses</a></li>
                    <li><a href="/account/change-password" class="<?= $currentMenu === 2 ? 'active-link' : '' ?>">Change Password</a></li>
                </ul>
            </li>
            <li><a href="/orders"><i class="fas fa-clipboard-list"></i> My Purchase</a></li>
        </ul>
    </nav>
</aside>
