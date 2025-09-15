<?php
declare(strict_types=1);

use App\Entity\User\User;

assert(isset($user) && $user instanceof User);
assert(isset($otp) && is_string($otp));
?>

<style>
    .container {
        max-width: 600px;
        margin: auto;
        padding: 20px;
        border: 1px solid #eee;
        border-radius: 8px;
    }
    .otp {
        font-size: 20px;
        font-weight: bold;
        letter-spacing: 4px;
        color: #2a7ae2;
    }
</style>

<div class="container">
    <h2>Verify Your Email</h2>
    <p>Enter this one-time code in the verification page:</p>
    <p class="otp"><?= htmlspecialchars($otp) ?></p>
    <p>This code is valid for one use only.</p>
</div>
