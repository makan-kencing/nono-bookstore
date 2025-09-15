<?php

declare(strict_types=1);

use App\Core\View;
use App\Entity\User\User;

assert(isset($user) && $user instanceof User);

ob_start();
?>
    <main>
        <div>
            <aside>
                <?= View::render('admin/user/_sidebar.php', ['currentMenu' => 'Orders', 'user' => $user]) ?>
            </aside>

            <section>
                <h1>Order Detail List</h1>

                <div id="output-table">
                    <table>
                        <thead>
                        <tr>
                            <th>Num</th>
                            <th>Ref No</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Shipment Status</th>
                        </tr>
                        </thead>
                        <tbody><?php $num = 1; ?>
                        <?php foreach ($user->orders as $order): ?>
                            <tr data-id="<?= $order->id ?>">
                                <td><?= $num ?></td>
                                <td><?= $order->refNo ?></td>
                                <td><?= count($order->items) ?></td>
                                <td><?= number_format($order->getTotal() / 100, 2) ?></td>
                                <td>
                                <span class="chip"
                                      data-order-status="<?= strtolower($order->getOrderStatus()->name) ?>">
                                    <?= $order->getOrderStatus()->toDescription() ?>
                                </span>
                                </td>
                            </tr>
                            <?php $num++; ?>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </main>
    <script>
        $("table.user-table tbody tr").click(/** @param {jQuery.Event} e */(e) => {
            window.location = `/admin/order/${e.currentTarget.dataset.id}`;
        })

    </script>

<?php

$title = 'User Orders';
$content = ob_get_clean();

echo View::render(
    'admin/_base.php',
    ['title' => $title, 'content' => $content]
);


