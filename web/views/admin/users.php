<?php

declare(strict_types=1);

assert(isset($users) && is_array($users));

$title = 'Users';

ob_start();
?>
    <?= '<pre>' . var_export($users, true) . '</pre>' ?>
<?php
$content = ob_get_clean();

include __DIR__ . "/../_base.php";
