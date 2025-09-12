<?php

declare(strict_types=1);

use App\Core\Template;
use App\Core\View;

$template = new Template(
    '_base.php',
    ['title' => $title ?? '']
);

?>

<?php $template->startFragment('header') ?>

<link rel="stylesheet" href="/static/styles/webstore.css">
<link rel="stylesheet" href="/static/styles/user-table.css">

<?php $template->endFragment() ?>

<?php $template->start() ?>

<?= View::render('webstore/_header.php'); ?>
<?= $body ?? '' ?>
<?= View::render('webstore/_footer.php'); ?>

<?= $template->end() ?>
