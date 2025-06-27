<?php declare(strict_types=1); ?>

<?php
$title = "Not Found";

ob_start();
?>
    <h2>Oops, you reached a dead end</h2>
    <p>Let's <a href="/">go back</a> shall we?</p>
<?php
$content = ob_get_clean();

include __DIR__ . "/../_base.php";
