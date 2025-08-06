<?php

declare(strict_types=1);

ob_start();
?>

<link rel="stylesheet" href="/static/styles/webstore.css">

<?php
$extraHead = ob_get_clean();

ob_start();
?>

<?php include __DIR__ . "/_header.php" ?>

<?= $content ?? '' ?>

<?php include __DIR__ . "/_footer.php" ?>

<?php
$content = ob_get_clean();

include __DIR__ . "/../_base.php";
