<?php

declare(strict_types=1);

use App\DTO\Request\BookSearchDTO;
use App\DTO\Response\PageResultDTO;
use App\Entity\Book\Author\AuthorDefinition;
use App\Entity\Book\Book;
use App\Entity\Book\BookImage;
use App\Entity\Book\Category\CategoryDefinition;

assert(isset($page) && $page instanceof PageResultDTO);
assert(isset($search) && $search instanceof BookSearchDTO);
/** @var PageResultDTO<Book> $page */

?>

<table>
    <thead>
    <tr>
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
    </tr>
    </thead>

    <tbody>
    <?php $i = $page->getStartIndex() + 1 ?>
    <?php foreach ($page->items as $item): ?>
        <?php
        usort(
            $item->images,
            fn(BookImage $o1, BookImage $o2) => $o1->imageOrder - $o2->imageOrder
        );
        usort(
            $item->authors,
            function (AuthorDefinition $o1, AuthorDefinition $o2) {
                if ($o1->type === null) return -1;
                if ($o2->type === null) return 1;
                return $o1->type->compareTo($o2->type);
            }
        );

        $price = $item->getCurrentPrice();
        $image = $item->images[0] ?? null;
        ?>

        <tr>
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
                    fn (AuthorDefinition $author) => "<span>{$author->author->name}</span>",
                    $item->authors
                ));
                ?>
            </td>
            <td>
                <?=
                implode(', ', array_map(
                    fn (CategoryDefinition $category) => "<span>{$category->category->name}</span>",
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
    </tbody>

    <tfoot>
    <tr>
       <td colspan="11">
           <div style="display: flex; align-items: center; gap: 1rem">
               <div style="margin-right: auto;">
                   <?= $page->getStartIndex() + 1 ?> - <?= $page->getEndIndex() + 1 ?>
                   / <?= $page->total ?>
               </div>

               <div>
                   <label>
                       Records per page
                       <select name="page_size" onchange="reloadTable()">
                           <?php $sizes = [10, 25, 50] ?>
                           <?php foreach ($sizes as $size): ?>
                               <option value="<?= $size ?>"
                                   <?= $size === $search->pageSize ? 'selected' : '' ?>>
                                   <?= $size ?>
                               </option>
                           <?php endforeach; ?>
                       </select>
                   </label>
               </div>

               <input type="hidden" name="page" value="<?= $page->pageRequest->page ?>">

               <div>
                   <button onclick="gotoPreviousPage()" type="button" <?= !$page->hasPreviousPage() ? 'disabled' : '' ?>><</button>
                   <?php foreach ($page->getSlidingPageWindow() as $pageRequest): ?>
                       <button onclick="gotoPage(<?= $pageRequest->page ?>)" type="button"
                           <?= $pageRequest->page === $page->pageRequest->page ? 'disabled' : '' ?>>
                           <?= $pageRequest->page ?>
                       </button>
                   <?php endforeach; ?>
                   <button onclick="gotoNextPage()" type="button" <?= !$page->hasNextPage() ? 'disabled' : '' ?>>></button>
               </div>
           </div>
       </td>
    </tr>
    </tfoot>
</table>
