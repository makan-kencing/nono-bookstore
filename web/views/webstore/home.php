<?php declare(strict_types=1);

use App\Core\Template;
use App\Core\View;
use App\DTO\Response\PageResultDTO;
use App\Entity\Book\Book;

assert(isset($newest) && $newest instanceof PageResultDTO);
assert(isset($top) && $top instanceof PageResultDTO);
assert(isset($books) && $books instanceof PageResultDTO);
/** @var PageResultDTO<Book> $newest */
/** @var PageResultDTO<Book> $top */
/** @var PageResultDTO<Book> $books */

$template = new Template(
    'webstore/_base.php',
    ['title' => 'Home']
);

?>

<?php $template->startFragment('header'); ?>

<link rel="stylesheet" href="/static/styles/webstore/home.css">

<?php $template->endFragment(); ?>

<?php $template->start() ?>
<main>
    <div>
        <h2><i>Newest releases</i></h2>

        <div class="product-list scroll">
            <?php foreach ($newest->items as $item): ?>
                <?= View::render('webstore/_component/_search_book_item.php', ['book' => $item]) ?>
            <?php endforeach; ?>
        </div>
    </div>

    <div>
        <h2><i>You would like...</i></h2>

        <div class="product-list scroll">
            <?php foreach ($top->items as $item): ?>
                <?= View::render('webstore/_component/_search_book_item.php', ['book' => $item]) ?>
            <?php endforeach; ?>
        </div>
    </div>

    <div>
        <h2><i>Browse Our Collection</i></h2>

        <div class="product-grid">
            <?php foreach ($books->items as $item): ?>
                <?= View::render('webstore/_component/_search_book_item.php', ['book' => $item]) ?>
            <?php endforeach; ?>
        </div>

        <div class="find-more"><a href="/books/search">Find more</a></div>
    </div>
</main>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        for (let c of document.querySelectorAll(".scroll")) {
            if (!c) return;

            if (c.scrollWidth <= c.clientWidth) c.innerHTML += c.innerHTML;

            const PX_PER_SEC = 20;
            let pos = c.scrollLeft;
            let last = performance.now();
            let paused = false;

            function tick(now) {
                if (!paused) {
                    const dt = now - last;
                    pos += (PX_PER_SEC * dt) / 1000;
                    const max = c.scrollWidth - c.clientWidth;
                    if (pos >= max - 1) pos = 0;
                    c.scrollLeft = Math.floor(pos);
                }
                last = now;
                rafId = requestAnimationFrame(tick);
            }

            let rafId = requestAnimationFrame(tick);

            c.addEventListener("mouseenter", () => {
                paused = true;
            });

            c.addEventListener("mouseleave", () => {
                paused = false;
                last = performance.now();
            });
        }
    });
</script>
<?= $template->end() ?>
