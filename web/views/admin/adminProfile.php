<?php

declare(strict_types=1);

$title = 'profile';

ob_start();
?>

<section>
    <h2>主要内容</h2>
    <p>Test for highper link</p>
</section>

<?php
$content = ob_get_clean();

include __DIR__ . "/_base.php";


