<?php declare(strict_types=1);


use App\Core\View;
use App\DTO\Response\PageResultDTO;
use App\Entity\Book\Book;

assert(isset($newest) && $newest instanceof PageResultDTO);
assert(isset($top) && $top instanceof PageResultDTO);
/** @var $newest PageResultDTO<Book> */
/** @var $top PageResultDTO<Book> */


ob_start();
?>
    <main style="display: flex; flex-flow: column; gap: 2rem;">
        <div style="display: flex; flex-flow: column; gap: 1rem;">
            <h2>Newest releases</h2>

            <div style="display: grid; overflow-x: auto; gap: 5rem; grid-template-columns: repeat(auto-fill,minmax(160px,1fr)); grid-auto-flow: column; grid-auto-columns: minmax(160px,1fr);">
                <?php foreach ($newest->items as $item): ?>
                    <?= View::render('webstore/_component/_search_book_item.php', ['book' => $item]) ?>
                <?php endforeach; ?>
            </div>
        </div>

        <div style="display: flex; flex-flow: column; gap: 1rem;">
            <h2>You would like</h2>

            <div style="display: grid; overflow-x: auto; gap: 5rem; grid-template-columns: repeat(auto-fill,minmax(160px,1fr)); grid-auto-flow: column; grid-auto-columns: minmax(160px,1fr);">
                <?php foreach ($top->items as $item): ?>
                    <?= View::render('webstore/_component/_search_book_item.php', ['book' => $item]) ?>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <style>
        img {
            width: 200px;
        }
    </style>
<?php
$title = "Home";
$content = ob_get_clean();

echo View::render(
    'webstore/_base.php',
    ['title' => $title, 'content' => $content]
);
