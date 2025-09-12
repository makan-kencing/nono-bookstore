<?php

declare(strict_types=1);

use App\Core\Template;

$template = new Template(
    'webstore/_base.php',
    ['title' => 'Not Found']
);

?>

<?php $template->start(); ?>
<main style="display: flex; flex-flow: column; justify-content: center; align-items: center; height: 60vh;">
    <div>
        <h2>Oops, you reached a dead end</h2>
        <p>Let's <a href="/">go back</a> shall we?</p>
    </div>
</main>
<?= $template->end(); ?>
