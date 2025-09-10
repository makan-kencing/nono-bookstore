<?php

declare(strict_types=1);

use App\Entity\Book\Author\AuthorDefinition;
use App\Entity\Book\BookImage;
use App\Entity\Cart\CartItem;

assert(isset($item) && $item instanceof CartItem);

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

$totalStocks = $book->getTotalInStock();
$price = $book->getCurrentPrice();
$author = $book->authors[0];
$image = $book->images[0] ?? null;

?>

<div style="display: flex; flex-flow: row nowrap; align-items: stretch">
    <div style="display: flex; justify-content: center; height: 200px;">
        <?php if ($image !== null): ?>
            <img src="<?= $image->file->filepath ?>" alt="<?= $image->file->alt ?>">
        <?php else: ?>
            <img src="" alt="">
        <?php endif; ?>
    </div>

    <div>
        <h3><a href="/book/<?= $book->isbn ?>/<?= $book->work->slug ?>"><?= $book->work->title ?></a></h3>

        <p>by <?= $author->author->name ?></p>

        <?php if ($totalStocks > 20): ?>
            <p>In Stock</p>
        <?php elseif ($totalStocks > 0): ?>
            <p><?= $totalStocks ?> left</p>
        <?php else: ?>
            <p>No stock</p>
        <?php endif; ?>

        <span style="font-weight: bold"><?= $price?->amount / 100 ?></span>
    </div>
</div>
