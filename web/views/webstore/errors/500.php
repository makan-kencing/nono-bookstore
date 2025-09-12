<?php

declare(strict_types=1);

use App\Core\Template;

$template = new Template(
    'webstore/_base.php',
    ['title' => 'Error']
);

?>

<?php $template->start(); ?>
<main style="display: flex; flex-flow: column; justify-content: center; align-items: center; height: 60vh;">
    <div>
        <h2>Oops, something went wrong</h2>
        <p>Please wait a moment while we fix the issue</p>
        <p><a href="/">Go back?</a></p>
    </div>
</main>
<?= $template->end(); ?>
