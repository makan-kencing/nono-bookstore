<?php

declare(strict_types=1);

use App\Entity\User\User;

assert(isset($user) && $user instanceof User);
assert(isset($url) && is_string($url));

$now = new DateTime();
?>

<h2>Reset your password</h2>

<p>Hello <?= $user->username ?>,</p>

<p>We received a password reset request for you account on <?= $now->format('r') ?>.</p>

<p><a href="<?= $url ?>">Reset Password</a></p>

<p>If you didn't request this, please ignore this email.</p>

<p>The link will expire in 1 hour.</p>
