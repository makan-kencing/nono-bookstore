<?php

declare(strict_types=1);

assert(isset($users) && is_array($users));

$title = 'Users';

ob_start();
?>

<?php xdebug_var_dump($users); ?>

<?php
$content = ob_get_clean();

include __DIR__ . "/../_base.php";
