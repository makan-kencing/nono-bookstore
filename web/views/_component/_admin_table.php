<?php

declare(strict_types=1);

use App\DTO\Request\SearchDTO;
use App\DTO\Response\PageResultDTO;

assert(isset($page) && $page instanceof PageResultDTO);
assert(isset($search) && $search instanceof SearchDTO);

// fragments
assert(isset($header) && is_string($header));
assert(isset($body) && is_string($body));

?>

<table id="data-tables">
    <thead>
        <?= $header ?>
    </thead>
    <tbody>
        <?= $body ?>
    </tbody>
    <tfoot>
    <tr>
        <td colspan="100%">
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
                    <button onclick="table.previousPage()" type="button" <?= !$page->hasPreviousPage() ? 'disabled' : '' ?>><</button>
                    <?php foreach ($page->getSlidingPageWindow() as $pageRequest): ?>
                        <button onclick="table.page(<?= $pageRequest->page ?>)" type="button"
                            <?= $pageRequest->page === $page->pageRequest->page ? 'disabled' : '' ?>>
                            <?= $pageRequest->page ?>
                        </button>
                    <?php endforeach; ?>
                    <button onclick="table.nextPage()" type="button" <?= !$page->hasNextPage() ? 'disabled' : '' ?>>></button>
                </div>
            </div>
        </td>
    </tr>
    </tfoot>
</table>
