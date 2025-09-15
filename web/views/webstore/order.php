<?php

declare(strict_types=1);

use App\Core\Template;
use App\Core\View;
use App\Entity\Order\Order;

assert(isset($order) && $order instanceof Order);

$template = new Template(
    'webstore/_base.php',
    ['title' => 'Order #' . $order->id]
);

?>

<?php $template->startFragment('header'); ?>

<link rel="stylesheet" href="/static/styles/webstore/cart.css">

<?php $template->endFragment(); ?>

<?php $template->start() ?>
<main>

    <div>
        <h2>Order Details</h2>
        <div style="display: flex; gap: 2rem">
            <h4>Order placed <?=$order->orderedAt->format('F j,y')?></h4>
            <div>|</div>
            <h4>RefNo# <?=$order->refNo?></h4>
        </div>
    </div>

    <section style="display: flex; justify-content: space-between; padding: 1rem; border: 1px solid black; border-radius: 0.2rem;">
        <div style="display: flex; flex-flow: column" >
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

        <div style="display: flex; flex-flow: column">
            <h4>Payment method</h4>
                                <p><?=$order->invoice->payment->method?></p>
        </div>

        <div style="display: flex; flex-flow: column" >
            <h4>Order Summary</h4>
            <p>Item(s)      : RM <?=number_format($order->getSubtotal() / 100, 2)?></p>
            <p>Shipping Fee : RM <?=number_format($order->getShipping() / 100, 2)?></p>
            <p><b>Grand Total  : RM <?=number_format($order->getTotal() / 100,2)?></b></p>
        </div>

        <div style="display: flex; flex-flow: column">
            <h4>ShipmentStatus</h4>
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
        </div>
    </section>

    <section style="padding: 1rem; border: 1px solid black; border-radius: 0.2rem;">
        <table>
            <tr>
                <td>
                    <div>
                        <?php foreach ($order->items as $item): ?>
                            <?= View::render('webstore/_component/_order_detail.php', ['item' => $item]) ?>
                        <?php endforeach;?>
                    </div>
                </td>
            </tr>
        </table>
    </section>
</main>
<?= $template->end() ?>
