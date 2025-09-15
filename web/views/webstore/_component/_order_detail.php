<?php

declare(strict_types=1);

use App\Entity\Book\Author\AuthorDefinition;
use App\Entity\Book\BookImage;
use App\Entity\Order\Order;
use App\Entity\Order\OrderItem;

assert(isset($item) && $item instanceof OrderItem);

$book = $item->book;

usort(
    $book->images,
    fn(BookImage $o1, BookImage $o2) => $o1->imageOrder - $o2->imageOrder
);
usort(
    $book->authors,
    function (AuthorDefinition $o1, AuthorDefinition $o2) {
        if ($o1->type === null) return -1;
        if ($o2->type === null) return 1;
        return $o1->type->compareTo($o2->type);
    }
);

$author = $book->authors[0];
$image = $book->images[0] ?? null;

?>

<div style="display: flex; flex-flow: row nowrap; gap: 1rem; align-items: stretch; padding: 1rem">
    <div style="display: flex; justify-content: center; height: 100px;">
        <?php if ($image !== null): ?>
            <img src="<?= $image->file->filepath ?>" alt="<?= $image->file->alt ?>">
        <?php else: ?>
            <img src="" alt="">
        <?php endif; ?>
    </div>

    <div>
        <h3><a href="/book/<?= $book->isbn ?>/<?= $book->work->slug ?>"><?= $book->work->title ?></a></h3>
        <p>by <?= $author->author->name ?></p>
        <p>RM <?=number_format($item->unitPrice / 100, 2)?></p>

    </div>
</div>
