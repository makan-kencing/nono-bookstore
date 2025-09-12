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
                            <div>SHIP TO</div>
                            <div><?= $order->address->name ?></div>
                        </div>

                        <div style="text-align: right; margin-left: auto;">
                            <div>ORDER #<?= $order->id ?></div>
                            <div><a href="/order/<?= $order->id ?>">View order details</a></div>
                        </div>
                    </div>

                    <div style="display: flex; flex-flow: column; gap: 0.5rem;">
                        <?php foreach ($order->items as $item): ?>
                            <?= View::render('webstore/_component/_order_history_item.php', ['item' => $item]) ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>
<?= $template->end() ?>

