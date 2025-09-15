<?php

declare(strict_types=1);

use App\Entity\User\User;

assert(isset($user) && $user instanceof User);
assert(isset($currentMenu) && is_int($currentMenu));

?>
<link rel="stylesheet" href="/static/styles/Account/sidebar.css">
<aside class="sidebar">
    <div class="user-info">
        <div class="avatar">
            <?php if (empty($user->image?->filepath)): ?>
                <img src="/static/assets/default-user.jpg" alt="Default Avatar">
            <?php else: ?>
                <img src="<?= htmlspecialchars($user->image->filepath) ?>" alt="Profile Image" class="qr-code">
            <?php endif; ?>
        </div>
        <div class="user-details">
            <strong><?= htmlspecialchars($user->username ?? '') ?></strong>
            <a href="/account"><i class="fas fa-pencil-alt"></i> Edit Profile</a>
        </div>
    </div>
    <nav class="sidebar-nav">
        <ul>
            <li><a href="/account" class="active"><i class="far fa-user"></i> My Account</a>
                <ul class="submenu">
                    <li><a href="/account" class="<?= $currentMenu === 0 ? 'active-link' : '' ?>">Profile</a></li>
                    <li><a href="/account/addresses" class="<?= $currentMenu === 1 ? 'active-link' : '' ?>">Addresses</a></li>
                    <li><a href="/account/change-password" class="<?= $currentMenu === 2 ? 'active-link' : '' ?>">Change Password</a></li>
                    <li><a href="/account/2fa" class="<?= $currentMenu === 3 ? 'active-link' : '' ?>">Register 2FA</a></li>
                </ul>
            </li>
            <li><a href="/orders"><i class="fas fa-clipboard-list"></i> My Purchase</a></li>
        </ul>
    </nav>
</aside>
