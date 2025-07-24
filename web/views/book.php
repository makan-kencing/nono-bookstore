<?php

declare(strict_types=1);

use App\Entity\Book\Book;

assert(isset($book) && $book instanceof Book);

$title = $book->title ?? 'Book';

ob_start();
?>
    <?= '<pre>' . var_export($book, true) . '</pre>' ?>
<?php
$content = ob_get_clean();

include __DIR__ . "/_base.php";
