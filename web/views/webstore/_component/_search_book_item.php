<?php

declare(strict_types=1);

use App\Entity\Book\Author\AuthorDefinition;
use App\Entity\Book\Book;

assert(isset($book) && $book instanceof Book);

$book->normalizeOrder();

$totalStocks = $book->getTotalInStock();
$price = $book->getCurrentPrice();
$image = $book->images[0] ?? null;

?>

<div class="product-item">
    <div class="product-image">
        <a href="/book/<?= $book->isbn ?>/<?= $book->work->slug ?>">
            <?php if ($image !== null): ?>
                <img src="<?= $image->file->filepath ?>" alt="<?= $image->file->alt ?>">
            <?php else: ?>
                <img src="https://s.gr-assets.com/assets/nophoto/book/111x148-bcc042a9c91a29c1d680899eff700a03.png" alt="">
            <?php endif; ?>
        </a>
    </div>

    <div class="product-info">
        <h3><a href="/book/<?= $book->isbn ?>/<?= $book->work->slug ?>"><?= $book->work->title ?></a></h3>

        <div>by
            <?=
            implode(',', array_map(
                fn(AuthorDefinition $author) => "<span>{$author->author->name}</span>",
                $book->authors
            ))
            ?>
        </div>

        <p><?= $book->coverType->title() ?></p>

        <?php if ($price !== null): ?>
            <p class="price">RM <?= number_format($price->amount / 100, 2) ?></p>
        <?php endif; ?>
    </div>
</div>
