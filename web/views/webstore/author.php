<?php

declare(strict_types=1);

use App\Core\Template;

$template = new Template(
    'webstore/_base.php',
    ['title' => $title ?? '']
);

?>

<?php $template->start(); ?>

    <p></p>

<?= $template->end() ?>
