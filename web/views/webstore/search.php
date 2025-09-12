<?php

declare(strict_types=1);

use App\Core\Template;
use App\Core\View;
use App\DTO\Request\BookSearchDTO;
use App\DTO\Request\BookSearchSortOption;
use App\DTO\Response\PageResultDTO;
use App\Entity\Book\Book;

assert(isset($page) && $page instanceof PageResultDTO);
assert(isset($search) && $search instanceof BookSearchDTO);
/** @var PageResultDTO<Book> $page */

$template = new Template(
    'webstore/_base.php',
    ['title' => 'Searching']
);

?>

<?php $template->start(); ?>
    <main style="display: flex; flex-flow: column; align-items: center;">
        <div style="display: flex; gap: 2rem;">
            <div>
                <details>
                    <summary>Category</summary>

                    <ul>
                        <li></li>
                    </ul>
                </details>

                <details>
                    <summary>Format</summary>

                    <ul>
                        <li></li>
                    </ul>
                </details>

                <details>
                    <summary>Price</summary>

                    <ul>
                        <li></li>
                    </ul>
                </details>

                <details>
                    <summary>Author</summary>

                    <ul>
                        <li></li>
                    </ul>
                </details>

                <details>
                    <summary>Publisher</summary>

                    <ul>
                        <li></li>
                    </ul>
                </details>

                <details>
                    <summary>Language</summary>

                    <ul>
                        <li></li>
                    </ul>
                </details>
            </div>

            <div style="font-size: 0.8rem; display: flex; flex-flow: column; gap: 2rem;">
                <div style="display: flex; align-items: stretch; gap: 1rem;">
                    <div style="margin-right: auto;">
                        <?= $page->getStartIndex() + 1 ?> - <?= $page->getEndIndex() + 1 ?>
                        of <?= $page->total ?> for <span style="font-weight: bold"><?= $search->query ?></span>
                    </div>

                    <label>
                        Show:
                        <select name="page_size" id="page-size">
                            <?php $sizes = [10, 25, 50] ?>
                            <?php foreach ($sizes as $size): ?>
                                <option value="<?= $size ?>"
                                    <?= $size === $search->pageSize ? 'selected' : '' ?>>
                                    <?= $size ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>

                    <label>
                        Sort by:
                        <select name="sort" id="sort">
                            <?php foreach (BookSearchSortOption::cases() as $option): ?>
                                <option value="<?= $option->name ?>"><?= $option->asLabel() ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); grid-auto-rows: 1fr; gap: 2rem;">
                    <?php foreach ($page->items as $book): ?>
                        <?= View::render('webstore/_component/_search_book_item.php', ['book' => $book]) ?>
                    <?php endforeach; ?>
                </div>

                <div style="display: flex; justify-content: center; gap: 1rem; font-size: 1rem;">
                    <?php if ($page->hasPreviousPage()): ?>
                        <a href="/books/search/<?= $search->query ?? '' ?><?= $search->withPage($search->page - 1)->toQueryString() ?>"><</a>
                    <?php else: ?>
                        <span><</span>
                    <?php endif; ?>

                    <?php foreach ($page->getSlidingPageWindow() as $pageRequest): ?>
                        <?php if ($pageRequest->page === $page->pageRequest->page): ?>
                            <span><?= $pageRequest->page ?></span>
                        <?php else: ?>
                            <a href="/books/search/<?= $search->query ?? '' ?><?= $search->withPage($pageRequest->page)->toQueryString() ?>"><?= $pageRequest->page ?></a>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <?php if ($page->hasNextPage()): ?>
                        <a href="/books/search/<?= $search->query ?? '' ?><?= $search->withPage($search->page + 1)->toQueryString() ?>">></a>
                    <?php else: ?>
                        <span>></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
<?= $template->end() ?>
