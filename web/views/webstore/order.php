<?php

declare(strict_types=1);

use App\Entity\Order\Order;

assert(isset($order) && $order instanceof Order);

$title = 'Order #0000';

ob_start();
?>
    <div>
        <h3>Order Details</h3>
        <div>
            <nav>
            <p>Order placed <?=$order->orderedAt->format('F j,y')?></p>|
            <p>RefNo#<?=$order->refNo?></p>
            </nav>
        </div>
        <div>
            <div>
                <h4>Ship to</h4>
                <p><?=$order->address->name?></p>
                <p><?=$order->address->address1?></p>
                <?php if(!$order->address->address2): ?>
                    <p><?=$order->address->address2?><p>
                <?php endif; ?>
                <?php if(!$order->address->address3): ?>
                    <p><?=$order->address->address3?></p>
                <?php endif; ?>
                <p><?=$order->address->state?>,<?=$order->address->postcode?></p>
                <p><?=$order->address->country?></p>
            </div>
            <div>
                <h4>Payment method</h4>
                <p><?=$order->invoice->payment->method?></p>
            </div>
            <div>
                <h4>Order Summary</h4>
                <p>Item(s)      : RM<?=$order->getSubtotal()?></p>
                <p>Shipping Fee : RM<?=$order->getShipping()?></p>
                <p>Grand Total  : RM<?=$order->getTotal()?></p>
            </div>
        </div>
    </div>
    <div>

    </div>

<?php
$content = ob_get_clean();

include __DIR__ . "/_base.php";
