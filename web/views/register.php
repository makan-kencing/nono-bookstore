<?php declare(strict_types=1);

$title = 'Register';

ob_start();
?>
    <div>
        <h2>Register</h2>
        <form method="POST" action="/register">
            <div class="mb-3">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Register</button>
        </form>
    </div>
<?php
$content = ob_get_clean();

include __DIR__ . "/_base.php";
