<?php
declare(strict_types=1);

use App\Entity\Order\Order;

/** @var Order[] $orders */
assert(isset($orders) && is_array($orders));


$title = "Orders";
ob_start();
?>
    <section class="profile-container">
        <div class="profile-card">

            <div class="table-wrapper">
                <table class="user-table" id="user-table">
                    <thead>
                    <tr>
                        <th>Num</th>
                        <th>Username</th>
                        <th>Ref No</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Shipment Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $num = 1; ?>
                    <?php foreach ($orders as $order) : ?>
                        <tr>
                            <td><?= $num ?></td>
                            <td><?= $order->user->username ?></td>
                            <td><?= $order->refNo ?></td>
                            <?php foreach ($order->items as $item) : ?>
                                <td><?= $item->quantity ?></td>
                            <?php endforeach; ?>
                            <?php foreach ($order->adjustments as $adjustment) : ?>
                                <td><?= $adjustment->amount ?></td>
                            <?php endforeach; ?>
                            <td class="chip">
                                <?php if ($order->shipment == null) : ?>
                                    <span class="chip chip-error">No Shipment</span>
                                <?php elseif (!$order->shipment->readyAt) : ?>
                                    <span class="chip chip-preparing">Preparing</span>
                                <?php elseif ($order->shipment->readyAt && !$order->shipment->shippedAt) : ?>
                                    <span class="chip chip-ready">Ready To Ship</span>
                                <?php elseif ($order->shipment->shippedAt && !$order->shipment->deliveredAt) : ?>
                                    <span class="chip chip-shipped">Shipped</span>
                                <?php elseif ($order->shipment->deliveredAt) : ?>
                                    <span class="chip chip-delivered">Delivered</span>
                                <?php else : ?>
                                    <span class="chip chip-error">Error</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php $num++; ?>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <script>
        $("table.user-table tbody tr").click(/** @param {jQuery.Event} e */ (e) => {
            window.location = `/admin/order/${e.currentTarget.dataset.id}`;
        })
    </script>

<?php
$content = ob_get_clean();
include __DIR__ . "/_base.php";
