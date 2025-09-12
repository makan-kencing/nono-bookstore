<?php

declare(strict_types=1);

use App\Core\Template;
use App\Core\View;

$template = new Template(
    '_base.php',
    ['title' => $title ?? '']
);

?>

<?php $template->start() ?>
<?= View::render('auth/_header.php'); ?>
<?= $content ?? '' ?>
<?= View::render('auth/_footer.php'); ?>
<?= $template->end() ?>
