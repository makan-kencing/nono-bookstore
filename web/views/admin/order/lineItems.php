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
                        <?php foreach ($order->items as $item) : ?>
                            <tr data-id="<?=$item->id?>">
                                <td>

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


    </div>

<?php

$title = 'Order Order';
$content = ob_get_clean();

echo View::render(
    'admin/_base.php',
    ['title' => $title, 'content' => $content]
);






