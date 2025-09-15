<?php

declare(strict_types=1);

use App\Core\Template;
use App\DTO\Request\BookSearchDTO;
use App\DTO\Response\PageResultDTO;
use App\Entity\Book\Author\AuthorDefinition;
use App\Entity\Book\Book;
use App\Entity\Book\Category\CategoryDefinition;

assert(isset($page) && $page instanceof PageResultDTO);
assert(isset($search) && $search instanceof BookSearchDTO);
/** @var PageResultDTO<Book> $page */

$template = new Template(
    '_component/_admin_table.php',
    ['page' => $page, 'search' => $search]
);

?>

<?php $template->startFragment('header'); ?>
<th></th>
<th></th>
<th>Title</th>
<th>ISBN</th>
<th>Format</th>
<th>Authors</th>
<th>Categories</th>
<th>Publisher</th>
<th>Published At</th>
<th>Current Price</th>
<th>Total Stocks</th>
<?php $template->endFragment(); ?>


<?php $template->start(); ?>
<?php $i = $page->getStartIndex() + 1 ?>
<?php foreach ($page->items as $item): ?>
    <?php
    $item->normalizeOrder();

    $price = $item->getCurrentPrice();
    $image = $item->images[0] ?? null;
    ?>

    <tr data-id="<?= $item->id ?>" onclick="window.location = `/admin/book/${this.dataset.id}`">
        <td>
            <?= $i++ ?>
        </td>
        <td>
            <?php if ($image !== null): ?>
                <img src="<?= $image->file->filepath ?>" alt="<?= $image->file->alt ?>">
            <?php else: ?>
                <img src="" alt="">
            <?php endif; ?>
        </td>
        <td>
            <div>
                <a href="/admin/book/<?= $item->id ?>"><?= $item->work->title ?></a>
            </div>
            <?php if ($item->work->series !== null): ?>
                <div>Part of: <?= $item->work->series->series->name ?></div>
            <?php endif; ?>
        </td>
        <td><?= $item->isbn ?></td>
        <td><?= $item->coverType->title() ?></td>
        <td>
            <?=
            implode(', ', array_map(
                fn(AuthorDefinition $author) => "<span title='{$author->type?->title()}'>{$author->author->name}</span>",
                $item->authors
            ));
            ?>
        </td>
        <td>
            <?=
            implode(', ', array_map(
                fn(CategoryDefinition $category) => "<span>{$category->category->name}</span>",
                $item->work->categories
            ));
            ?>
        </td>
        <td><?= $item->publisher ?></td>
        <td><?= $item->publicationDate ?></td>
        <td>
            <?php if ($price): ?>
                RM <?= number_format($price->amount / 100, 2) ?>
            <?php endif; ?>
        </td>
        <td><?= $item->getTotalInStock() ?></td>
    </tr>
<?php endforeach; ?>
<?= $template->end() ?>

