<?php

declare(strict_types=1);

use App\Entity\Book\Author\AuthorDefinition;
use App\Entity\Book\Book;
use App\Entity\Book\BookImage;

assert(isset($book) && $book instanceof Book);

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
$image = $book->images[0] ?? null;

?>

<div style="display: flex; flex-flow: column; gap: 0.5rem;">
    <div style="display: flex; flex-flow: column; align-items: center;">
        <a href="/book/<?= $book->isbn ?>/<?= $book->work->slug ?>">
            <?php if ($image !== null): ?>
                <img src="<?= $image->file->filepath ?>" alt="<?= $image->file->alt ?>">
            <?php else: ?>
                <img src="" alt="">
            <?php endif; ?>
        </a>
    </div>

    <div style="margin-top: auto;">
        <h3 style="white-space: nowrap; text-overflow: ellipsis; overflow: hidden"><a href="/book/<?= $book->isbn ?>/<?= $book->work->slug ?>"><?= $book->work->title ?></a></h3>

        <div style="white-space: nowrap; text-overflow: ellipsis; overflow: hidden">by
            <?php foreach ($book->authors as $author): ?>
                <a href="/author/<?= $author->author->slug ?>"><?= $author->author->name ?></a>,
            <?php endforeach; ?>
        </div>

        <p ><?= $book->coverType->title() ?></p>

        <?php if ($price !== null): ?>
            <p style="font-weight: bold">RM <?= number_format($price->amount / 100, 2) ?></p>
        <?php endif; ?>
    </div>
</div>
