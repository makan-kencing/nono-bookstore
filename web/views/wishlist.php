<?php

declare(strict_types=1);

$title = 'Wishlist';

ob_start();
?>
    <p></p>
<?php
$content = ob_get_clean();

include __DIR__ . "/_base.php";
