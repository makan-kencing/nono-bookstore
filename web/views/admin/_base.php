<?php

declare(strict_types=1);

ob_start();
?>

    <link rel="stylesheet" href="/static/styles/Admin/adminPage.css">

    <link rel="stylesheet" href="/static/styles/Admin/users-table.css">
    <link rel="stylesheet" href="/static/styles/Admin/orders-table.css">

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
