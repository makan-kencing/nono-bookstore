<?php

declare(strict_types=1);

use App\Core\View;
use App\Entity\Order\Order;use App\Entity\Order\OrderStatus;

assert(isset($order) && $order instanceof Order);

ob_start();
?>


    <div class="order-center">
        <div class="glass-frame">
            <!-- move your existing flex container inside -->
            <div class="order-row">
                <!-- (this is your original content) -->
                <div style="display: flex; flex-flow: row; ">
                    <div class="order-right" id="cards-wrap">
                        <div class="card" id="order-detail-card">
                            <div class="card-title">Order Detail</div>

                            <table class="card-table">
                                <tbody>
                                <tr>
                                    <th style="width:160px">Reference</th>
                                    <td><?=$order->refNo?></td>
                                </tr>
                                <tr>
                                    <th>Customer</th>
                                    <td><?= $order->user->username?></td>
                                </tr>
                                <tr>
                                    <th>Order At</th>
                                    <td><?= $order->orderedAt->format('Y-m-d H:i:s')?></td>
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

                    <div>
                        <div style="float: right" data-id="<?=$order->id?>">
                            <button class="update-shipment">
                                <?php if (!$order->shipment->readyAt) : ?>
                                    Update Ready to Shipment
                                <?php elseif ($order->shipment->readyAt && !$order->shipment->shippedAt) : ?>
                                    Update to Ship Out
                                <?php elseif ($order->shipment->shippedAt && !$order->shipment->arrivedAt) : ?>
                                    Update to Arrived
                                <?php elseif ($order->shipment->arrivedAt) : ?>
                                    Completed
                                <?php else : ?>
                                    No shipment
                                <?php endif; ?>
                            </button>
                        </div>
                        <div class="table-wrapper">
                            <table class="user-table" id="user-table">
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
                                    <tr>
                                        <td><?= $num++ ?></td>
                                        <td><?= View::render('admin/_component/_line_item.php', ['item' => $item]) ?></td>
                                        <td><?=number_format($item->getSubtotal()/100,2)?></td>
                                        <td><?=$item->quantity?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>

                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>



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






