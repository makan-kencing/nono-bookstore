<?php

declare(strict_types=1);

use App\Core\Template;

$template = new Template(
    'webstore/_base.php',
    ['title' => 'Not Found']
);

?>

<?php $template->start(); ?>

<main>
    <div style="display:flex; align-items:center; justify-content:center; gap:40px;">
        <div>
            <h2>Oops, you reached a dead end</h2>
            <p>Let's <a href="/">go back</a> shall we?</p>
        </div>
        <div>
            <img src="/static/assets/404-illustration.jpg" alt="404"
                 style="max-width:350px; width:100%;">
        </div>
    </div>
</main>
<?= $template->end(); ?>
