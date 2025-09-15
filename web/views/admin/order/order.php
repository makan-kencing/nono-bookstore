<?php

declare(strict_types=1);

use App\Core\View;
use App\Entity\Book\Author\AuthorDefinition;
use App\Entity\Order\Order;

assert(isset($order) && $order instanceof Order);

ob_start();
?>

    <main>
        <div>
            <aside></aside>

            <section>

                <div style="display: flex; flex-flow: row;">
                    <div class="order-right" id="cards-wrap">
                        <div class="card" id="order-detail-card">
                            <div class="card-title">Order Detail</div>

                            <table class="card-table">
                                <tbody>
                                <tr>
                                    <th style="width:160px">Reference</th>
                                    <td><?= $order->refNo ?></td>
                                </tr>
                                <tr>
                                    <th>Customer</th>
                                    <td><?= $order->user->username ?></td>
                                </tr>
                                <tr>
                                    <th>Order At</th>
                                    <td><?= $order->orderedAt->format('Y-m-d H:i:s') ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="card" id="shipment-card">
                            <div class="card-title">Shipment Status</div>
                            <table class="card-table">
                                <tbody>
                                <tr>
                                    <th style="width:160px">Status</th>
                                    <td class="chip">
                            <span class="chip" data-order-status="<?= strtolower($order->getOrderStatus()->name) ?>">
                                <?= $order->getOrderStatus()->toDescription() ?>
                            </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="width:160px">Ready At</th>
                                    <td class="mono"><?= $order->shipment?->readyAt?->format('Y-m-d H:i:s') ?? '-' ?></td>
                                </tr>
                                <tr>
                                    <th style="width:160px">Shipped At</th>
                                    <td class="mono"><?= $order->shipment?->shippedAt?->format('Y-m-d H:i:s') ?? '-' ?></td>
                                </tr>
                                <tr>
                                    <th style="width:160px">Arrived At</th>
                                    <td class="mono"><?= $order->shipment?->arrivedAt?->format('Y-m-d H:i:s') ?? '-' ?></td>
                                </tr>

                                </tbody>
                            </table>
                        </div>

                        <div class="card" id="totals-card">
                            <div class="card-title">Totals</div>
                            <table class="card-table">
                                <tbody>
                                <tr>
                                    <th>Total Cost</th>
                                    <td class="mono">RM <?= number_format($order->getTotal() / 100, 2) ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div style="width: 100%; padding: 1rem; display: flex; flex-flow: column; gap: 1rem;">
                        <div style="display: flex;">
                            <h2>Order Items</h2>

                            <div data-id="<?= $order->id ?>" style="margin-left: auto">
                                <button class="update-shipment">
                                    <?php if ($order->shipment === null) : ?>
                                        No shipment
                                    <?php elseif (!$order->shipment->readyAt) : ?>
                                        Update Ready to Shipment
                                    <?php elseif (!$order->shipment->shippedAt) : ?>
                                        Update to Ship Out
                                    <?php elseif (!$order->shipment->arrivedAt) : ?>
                                        Update to Arrived
                                    <?php elseif ($order->shipment->arrivedAt) : ?>
                                        Completed
                                    <?php endif; ?>
                                </button>
                            </div>
                        </div>

                        <div id="output-table">
                            <table>
                                <thead>
                                <tr>
                                    <th>Num</th>
                                    <th>Book</th>
                                    <th>Price(RM)</th>
                                    <th>Quantity</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $num = 1; ?>
                                <?php foreach ($order->items as $item) : ?>
                                    <?php
                                    $book = $item->book;
                                    $book->normalizeOrder();

                                    $image = $book->images[0] ?? null;
                                    ?>
                                    <tr>
                                        <td><?= $num++ ?></td>
                                        <td>
                                            <div style="display: flex;">

                                                <?php if ($image !== null): ?>
                                                    <img src="<?= $image->file->filepath ?>"
                                                         alt="<?= $image->file->alt ?>"
                                                         style="height: 200px; object-fit: contain">
                                                <?php else: ?>
                                                    <img src="" alt="">
                                                <?php endif; ?>

                                                <div>
                                                    <p>
                                                        <a href="/admin/book/<?= $book->id ?>"><?= $book->work->title ?></a>
                                                    </p>

                                                    <p>by
                                                        <?=
                                                        implode(', ', array_map(
                                                            fn(AuthorDefinition $author) => "<span title='{$author->type?->title()}'>{$author->author->name}</span>",
                                                            $book->authors
                                                        ));
                                                        ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= number_format($item->getSubtotal() / 100, 2) ?></td>
                                        <td><?= $item->quantity ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>

    </main>


    <script>
        $(document).ready(() => {
            $("button.update-shipment").click((e) => {
                e.preventDefault();

                const row = e.target.closest("div[data-id]");
                const button = e.currentTarget;
                const id = row.dataset.id;

                $.ajax(`/api/orderList/${id}`, {
                    method: "PUT",
                    dataType: "json",
                    error: (jqXHR, textStatus, errorThrown) => {
                        console.error(jqXHR, textStatus, errorThrown);
                        switch (jqXHR.status) {
                            case 401:
                                alert("You are not logged in.");
                                break;
                            default:
                                alert("Update failed.");
                        }
                    },
                    success: () => {
                        alert("Shipment Status Update Successful");
                        window.location.reload();
                    }
                });
            });
        });
    </script>


<?php

$title = 'Order Order';
$content = ob_get_clean();

echo View::render(
    'admin/_base.php',
    ['title' => $title, 'content' => $content]
);






