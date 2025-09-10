<?php

declare(strict_types=1);

use App\Entity\User\User;

assert(isset($user) && $user instanceof User );
$title = 'Order History';

ob_start();
?>

    <div class="table-wrapper">
        <h1>Order Detail List</h1>
        <table class="user-table">
            <thead>
            <tr>
                <th>Num</th>
                <th>Ref No</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Shipment Status</th>
            </tr>
            </thead>
            <tbody><?php $num=1;?>

            <?php foreach ($user->orders as $order): ?>
                <tr data-id="<?= $order->id ?>">
                    <td><?= $num?></td>
                    <td><?=$order->refNo?></td>
                    <?php foreach ($order->items as $item): ?>
                        <td><?= $item->quantity?></td>
                    <?php endforeach; ?>
                </tr>
                <?php $num++;?>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<?php
$content = ob_get_clean();

include __DIR__ . "/_base.php";
