<?php

declare(strict_types=1);

use App\Entity\Book\Author\AuthorDefinition;
use App\Entity\Book\BookImage;
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

<div style="display: flex; flex-direction: column; gap: 1rem; padding: 1rem">
    <table>

    <tr style="display: flex; justify-content: flex-start; height: 100px;">
        <td  style="display:flex; align-items:flex-start; gap:0.75rem;">
            <?php if ($image !== null): ?>
                <img src="<?= $image->file->filepath ?>" alt="<?= $image->file->alt ?>" class="book-cover">
            <?php else: ?>
                <img src="" alt="">
            <?php endif; ?>
            <div class="book-info">
                <p><a <?= $book->isbn ?>/<?= $book->work->slug ?>"><?= $book->work->title ?></a></p>
                <p>by <?= $author->author->name ?></p>
            </div>
        </td>
    </tr>
    </table>
</div>
