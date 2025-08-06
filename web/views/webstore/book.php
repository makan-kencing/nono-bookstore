<?php

declare(strict_types=1);

use App\Entity\Book\Book;

assert(isset($book) && $book instanceof Book);

$title = $book->title ?? 'Book';

ob_start();
?>

<?php xdebug_var_dump($book); ?>

<?php
$content = ob_get_clean();

include __DIR__ . "/_base.php";
