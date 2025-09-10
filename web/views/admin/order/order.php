<?php

declare(strict_types=1);

use App\Core\View;
use App\Entity\Order\Order;

assert(isset($order) && $order instanceof Order);

ob_start();
?>
    <div style="display: flex; flex-flow: row; ">
        <div>
            <?= View::render('admin/order/_sidebar.php', ['currentMenu' => 'Order Details', 'order' => $order]) ?>
        </div>
        <div class="order-layout" id="order-stage">

            <div class="order-left"  id="vendor-box">
                <?php
                $username = trim($order->user->username ?? '');
                $nameParts = preg_split('/\s+/', $username, -1, PREG_SPLIT_NO_EMPTY);

                $initials = '';
                foreach ($nameParts as $part) {
                    if ($part !== '') {
                        $initials .= $part[0];
                        if (strlen($initials) === 2) {
                            break;
                        }
                    }
                }
                ?>
                <span class="avatar-initials"><?= strtoupper($initials) ?></span>
            </div>

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
                                <?php if ($order->shipment == null) : ?>
                                    <span class="chip chip-error">No Shipment</span>
                                <?php elseif (!$order->shipment->readyAt) : ?>
                                    <span class="chip chip-preparing">Preparing</span>
                                <?php elseif ($order->shipment->readyAt && !$order->shipment->shippedAt) : ?>
                                    <span class="chip chip-ready">Ready To Ship</span>
                                <?php elseif ($order->shipment->shippedAt && !$order->shipment->arrivedAt) : ?>
                                    <span class="chip chip-shipped">Shipped</span>
                                <?php elseif ($order->shipment->arrivedAt) : ?>
                                    <span class="chip chip-delivered">Delivered</span>
                                <?php else : ?>
                                    <span class="chip chip-error">Error</span>
                                <?php endif; ?>
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
            <div>
                <button style="float: right">Update Shipment to Ready</button>
<!--                <button>Update Shipment to Shipped</button>-->
<!--                <button>Update Shipment to Arrived</button>-->
            </div>

            <div class="table-wrapper">
                <table class="user-table" id="user-table">
                    <thead>
                    <tr>
                        <th>Num</th>
                        <th>Book</th>

                        <th>Quantity</th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php $num = 1; ?>
                    <tr>
                        <td><?=$num?></td>
                        <?php foreach ($order->items as $item) : ?>
                            <td><?= View::render('admin/_component/_line_item.php', ['item' => $item]) ?></td>

                            <td>
                                <?=$item->quantity?>
                            </td>
                            <?php $num++; ?>
                        <?php endforeach; ?>
                    </tr>
                    </tbody>

                </table>
            </div>
        </div>

    </div>

<?php

$title = 'Order Order';
$content = ob_get_clean();

echo View::render(
    'admin/_base.php',
    ['title' => $title, 'content' => $content]
);






