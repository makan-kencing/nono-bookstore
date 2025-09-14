<?php declare(strict_types=1);

use App\Core\Template;
use App\Core\View;
use App\DTO\Response\PageResultDTO;
use App\Entity\Book\Book;

assert(isset($newest) && $newest instanceof PageResultDTO);
assert(isset($top) && $top instanceof PageResultDTO);
assert(isset($books) && $books instanceof PageResultDTO);
/** @var $newest PageResultDTO<Book> */
/** @var $top PageResultDTO<Book> */

$template = new Template(
    'webstore/_base.php',
    ['title' => 'Home']
);

?>

<?php $template->start() ?>
    <main style="display: flex; flex-flow: column; gap: 2rem;">

        <div  class="my-section" style="display: flex; flex-flow: column; gap: 1rem;">
            <h2>Newest releases</h2>

            <div class="my-scroll" style="display: grid; overflow-x: auto; gap: 5rem; grid-template-columns: repeat(auto-fill,minmax(160px,1fr)); grid-auto-flow: column; grid-auto-columns: minmax(160px,1fr);">
                <?php foreach ($newest->items as $item): ?>
                    <?= View::render('webstore/_component/_search_book_item.php', ['book' => $item]) ?>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="my-section" style="display: flex; flex-flow: column; gap: 1rem;">
            <h2>You would like</h2>

            <div class="my-scroll-2" style="display: grid; overflow-x: auto; gap: 5rem; grid-template-columns: repeat(auto-fill,minmax(160px,1fr)); grid-auto-flow: column; grid-auto-columns: minmax(160px,1fr);">
                <?php foreach ($top->items as $item): ?>
                    <?= View::render('webstore/_component/_search_book_item.php', ['book' => $item]) ?>
                <?php endforeach; ?>
            </div>
        </div>
        <div style="display: flex; flex-flow: column; gap: 1rem;">
            <h2>Product List</h2>

            <div class="product-grid">
                <?php foreach ($books->items as $item): ?>
                    <div class="book-card">
                        <?= View::render('webstore/_component/_search_book_item.php', ['book' => $item]) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </main>

    <style>
        img {
            width: 200px;
        }


        .my-section { max-width: 1200px; margin: 0 auto; padding: 0 16px; }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 2rem;
        }

        .product-grid .book-card { width: 100%; }

        @media (max-width: 1200px) { .product-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); } }
        @media (max-width: 992px)  { .product-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
        @media (max-width: 768px)  { .product-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        @media (max-width: 480px)  { .product-grid { grid-template-columns: 1fr; } }

    </style>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const c = document.querySelector(".my-scroll");
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
    });

    document.addEventListener("DOMContentLoaded", () => {
        const c = document.querySelector(".my-scroll-2");
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
    });
</script>

<?= $template->end() ?>
