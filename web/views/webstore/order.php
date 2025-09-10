<?php

declare(strict_types=1);

use App\Entity\Order\Order;

assert(isset($order) && $order instanceof Order);

$title = 'Order #0000';

ob_start();
?>
    <p>This order belongs to <?= $order->user->username ?></p>
<?php
$content = ob_get_clean();

include __DIR__ . "/_base.php";
