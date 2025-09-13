<?php

declare(strict_types=1);

use App\Core\Template;
use App\Core\View;

$template = new Template(
    '_base.php',
    ['title' => $title ?? '']
);

?>

<?php $template->startFragment('header'); ?>

<link rel="stylesheet" href="/static/styles/Admin/adminPage.css">
<link rel="stylesheet" href="/static/styles/Admin/users-table.css">
<link rel="stylesheet" href="/static/styles/Admin/orders-table.css">
<link rel="stylesheet" href="/static/styles/Admin/book-styles.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php $template->endFragment(); ?>

<?php $template->start() ?>

<?= View::render('admin/_header.php'); ?>
<?= $content ?? '' ?>
<?= View::render('admin/_footer.php'); ?>

<?= $template->end() ?>
