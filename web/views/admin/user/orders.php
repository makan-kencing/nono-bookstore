<?php

declare(strict_types=1);

use App\Core\View;
use App\Entity\User\User;

assert(isset($user) && $user instanceof User );

ob_start();
?>
    <div style="display: flex; flex-flow: row; ">
        <div>
            <?= View::render('admin/user/_sidebar.php', ['currentMenu' => 'Orders', 'user' => $user]) ?>
        </div>

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
                            <td><?= count($order->items)?></td>
                            <td><?= number_format($order->getTotal()/100,2) ?></td>
                            <td>
                                <span class="chip" data-order-status="<?= strtolower($order->getOrderStatus()->name) ?>">
                                    <?= $order->getOrderStatus()->toDescription() ?>
                                </span>
                            </td>
                        </tr>
                        <?php $num++;?>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
    </div>

<script>
    $("table.user-table tbody tr").click(/** @param {jQuery.Event} e */ (e) => {
        window.location = `/admin/user/orders${e.currentTarget.dataset.id}`;
    })

</script>

<?php

$title = 'User Orders';
$content = ob_get_clean();

echo View::render(
    'admin/_base.php',
    ['title' => $title, 'content' => $content]
);


