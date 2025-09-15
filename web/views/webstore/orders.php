<?php

declare(strict_types=1);

use App\Core\Template;
use App\Core\View;

assert(isset($orders) && is_array($orders));

$template = new Template(
    'webstore/_base.php',
    ['title' => 'My Orders']
);

?>

<?php $template->start() ?>
<main style="display: flex; flex-flow: column; align-items: center; width: 100%;">
    <div style="max-width: 1280px; width: 100%">
        <div>
            <h2>Your Orders</h2>

            <!-- TODO: searching (eta: never) -->
        </div>

        <div style="display: flex; flex-flow: column; align-items: stretch; gap: 1rem">
            <?php foreach ($orders as $order): ?>
                <div style="border: 1px solid lightgray; border-radius: 2rem">
                    <div style="display: flex; gap: 2rem; padding: 1rem;">
                        <div>
                            <div>ORDER PLACED</div>
                            <div><?= $order->orderedAt->format('F j, Y') ?></div>
                        </div>

                        <div>
                            <div>TOTAL</div>
                            <div>RM <?= number_format($order->getTotal() / 100, 2) ?></div>
                        </div>

                        <div>
                            <div> Quantity </div>
                            <div><?= count($order->items) ?></div>
                        </div>

                        <div>
                            <div>SHIP TO</div>
                            <div><?= $order->address->name ?></div>
                        </div>



                        <div style="text-align: right; margin-left: auto;">
                            <div>ORDER #<?= $order->id ?></div>
                            <div><a href="/order/<?= $order->id ?>">View order details</a></div>
                        </div>
                    </div>

                    <div style="display: flex; gap: 0.5rem;">
                        <?php foreach (array_splice($order->items, 0, 5) as $item): ?>
                            <?php
                            $book = $item->book;
                            $book->normalizeOrder();
                            $image = $book->images[0] ?? null;
                            ?>
                            <img title="<?= $book->work->title ?>" src="<?= $image?->file?->filepath ?>" alt="<?= $image?->file?->alt ?>">
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (count($orders) == 0): ?>
                <div style="border: 1px solid lightgray; border-radius: 2rem">
                    <p>No orders~</p>

                    <a href="/">Start shopping.</a

                </div>
            <?php endif; ?>
        </div>
    </div>
</main>
<?= $template->end() ?>

