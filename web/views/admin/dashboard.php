<?php

declare(strict_types=1);

$title = 'Dashboard';

ob_start();
?>

   <h2>Profile</h2>



<?php
$content = ob_get_clean();

include __DIR__ . "/_base.php";
