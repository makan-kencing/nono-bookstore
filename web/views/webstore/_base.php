<?php

declare(strict_types=1);

use App\Core\Template;
use App\Core\View;

// fragments
$header ??= '';

$template = new Template(
    '_base.php',
    ['title' => $title ?? '']
);

?>

<?php $template->startFragment('header') ?>

<link rel="stylesheet" href="/static/styles/webstore.css">

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<?= $header ?>

<?php $template->endFragment() ?>

<?php $template->start() ?>

<?= View::render('webstore/_header.php'); ?>

<?= $body ?? '' ?>

<?= View::render('webstore/_footer.php'); ?>

<?= $template->end() ?>
