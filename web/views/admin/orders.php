<?php
declare(strict_types=1);

use App\Entity\Order\Order;

/** @var Order[] $orders */
assert(isset($orders) && is_array($orders));


$title = "Orders";
ob_start();
?>
    <main class="table">
        <section class=table_header">
            <h1>Order Detail List</h1>
        </section>
        <section class="table_body">
            <table>
                <thead>
                <tr>
                    <th>Num</th>
                    <th>Username</th>
                    <th>Order Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Shipment Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <?php $num = 1; ?>
                <?php foreach ($orders as $order) : ?>
                    <tbody>
                    <tr>
                        <td><?= $num ?></td>
                        <td><?= $order->user->username ?></td>

                        <?php foreach ($order->items as $item) : ?>
                            <?php $book = $item->book; ?>

                            <?php foreach ($book->images as $image) : ?>
                                <td><img src="<?= $image->file->filepath ?>" alt="<?= $image->file->alt?>"></td>
                                <td><?= $book->isbn ?></td>
                                <td><?= $item->quantity ?></td>
                                <td>
                                    <?php if($order->shipment==null) : ?>
                                        <?php ?>
                                    <?php else : ?>
                                        <?php if (!$order->shipment->readyAt) : ?>
                                            <span class="chip chip-ok">Preparing</span>
                                        <?php elseif ($order->shipment->readyAt) : ?>
                                            <span class="chip chip-ok">Ready To Ship</span>
                                        <?php elseif ($order->shipment->shippedAt) : ?>
                                            <span class="chip chip-ok">Ship Out</span>
                                        <?php elseif ($order->shipment->deliveredAt) : ?>
                                            <span class="chip chip-ok">Delivered</span>
                                        <?php else : ?>
                                            <span class="chip chip-no">Error</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <td>

                                </td>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </tr>
                    </tbody>
                    <?php $num++ ?>
                <?php endforeach; ?>

            </table>
        </section>
    </main>

<?php
$content = ob_get_clean();
include __DIR__ . "/_base.php";
