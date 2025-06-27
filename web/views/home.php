<?php declare(strict_types=1); ?>

<?php
$title = "Home";

ob_start();
?>
    <h2>Welcome to <?= $home ?? '' ?></h2>
    <p>Please enjoy your stay here</p>
<?php
$content = ob_get_clean();

include __DIR__ . "/_base.php";