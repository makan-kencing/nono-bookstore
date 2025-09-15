<?php

declare(strict_types=1);

use App\Core\Template;
use App\Core\View;
use App\DTO\Request\BookSearchDTO;
use App\DTO\Request\BookSearchSortOption;
use App\DTO\Response\PageResultDTO;
use App\Entity\Book\Book;
use App\Entity\Book\Category\Category;
use App\Entity\Product\CoverType;

assert(isset($page) && $page instanceof PageResultDTO);
assert(isset($search) && $search instanceof BookSearchDTO);
assert(isset($categories) && is_array($categories));

/**
 * @var PageResultDTO<Book> $page
 * @var Category[] $categories
 */

$template = new Template(
    'webstore/_base.php',
    ['title' => 'Searching']
);

?>

<?php $template->start(); ?>
<main>
    <form class="search">
        <div>
            <a href="<?= (new BookSearchDTO())->toQueryString() ?>">Reset Filters</a>

            <details>
                <summary>Category</summary>

                <?php if ($search->categoryId !== null): ?>
                    <input type="hidden" name="category_id" value="<?= $search->categoryId ?>">
                <?php endif; ?>

                <ul>
                    <?php foreach ($categories as $category): ?>
                        <li>
                            <a href="<?= $search->withCategoryId($category->id)->toQueryString() ?>"><?= $category->name ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </details>

            <details>
                <summary>Format</summary>

                <?php if ($search->format !== null): ?>
                    <input type="hidden" name="format" value="<?= $search->format->name ?>">
                <?php endif; ?>

                <ul>
                    <?php foreach (CoverType::cases() as $type): ?>
                        <li><a href="<?= $search->withCoverType($type)->toQueryString() ?>"><?= $type->title() ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </details>

            <details>
                <summary>Price</summary>
                <div>
                    <div>
                        <label for="min-price">Min</label>
                        <input type="number" name="min_price" id="min-price" step="0.01"
                            <?php if ($search->minPrice): ?>
                               value="<?= number_format((int)$search->minPrice / 100, 2) ?>">
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="max-price">Max</label>
                        <input type="number" name="max_price" id="max-price" step="0.01"
                            <?php if ($search->maxPrice): ?>
                                value="<?= number_format((int)$search->maxPrice / 100, 2) ?>"
                            <?php endif; ?>
                        >
                    </div>
                </div>

                <button type="submit">Set</button>
            </details>
        </div>

        <div style="font-size: 0.8rem; display: flex; flex-flow: column; gap: 2rem;">
            <div style="display: flex; align-items: stretch; gap: 1rem;">
                <div style="margin-right: auto;">
                    <?= $page->getStartIndex() + 1 ?> - <?= $page->getEndIndex() + 1 ?>
                    of <?= $page->total ?> for <span style="font-weight: bold"><?= $search->query ?></span>
                </div>

                <label>
                    <input type="hidden" name="page" value="<?= $search->page ?>">

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
                            <option value="<?= $option->name ?>" <?= $search->option === $option ? 'selected' : '' ?>>
                                <?= $option->asLabel() ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>
            </div>

            <div class="product-grid"
            ">
            <?php foreach ($page->items as $book): ?>
                <?= View::render('webstore/_component/_search_book_item.php', ['book' => $book]) ?>
            <?php endforeach; ?>
        </div>

        <div style="display: flex; justify-content: center; gap: 1rem; font-size: 1rem;">
            <?php if ($page->hasPreviousPage()): ?>
                <a href="<?= $search->query ?? '' ?><?= $search->withPage($search->page - 1)->toQueryString() ?>"><</a>
            <?php else: ?>
                <span><</span>
            <?php endif; ?>

            <?php foreach ($page->getSlidingPageWindow() as $pageRequest): ?>
                <?php if ($pageRequest->page === $page->pageRequest->page): ?>
                    <span><?= $pageRequest->page ?></span>
                <?php else: ?>
                    <a href="<?= $search->query ?? '' ?><?= $search->withPage($pageRequest->page)->toQueryString() ?>"><?= $pageRequest->page ?></a>
                <?php endif; ?>
            <?php endforeach; ?>

            <?php if ($page->hasNextPage()): ?>
                <a href="<?= $search->query ?? '' ?><?= $search->withPage($search->page + 1)->toQueryString() ?>">></a>
            <?php else: ?>
                <span>></span>
            <?php endif; ?>
        </div>
    </form>
</main>

<script>
    $("select").change(/** @param {jQuery.Event} e */ function (e) {
        this.closest("form").submit();
    })
</script>
<?= $template->end() ?>
