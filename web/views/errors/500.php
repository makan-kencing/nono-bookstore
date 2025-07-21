<?php

declare(strict_types=1);

$title = "Not Found";

ob_start();
?>
    <h2>Oops, something went wrong</h2>
    <p>Please wait a moment while we fix the issue</p>
    <p><a href="/">Go back?</a></p>
<?php
$content = ob_get_clean();

include __DIR__ . "/../_base.php";
