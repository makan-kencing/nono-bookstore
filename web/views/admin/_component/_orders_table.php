<?php

declare(strict_types=1);

use App\Core\Template;
use App\DTO\Request\SearchDTO;
use App\DTO\Response\PageResultDTO;
use App\Entity\Order\Order;


assert(isset($page) && $page instanceof PageResultDTO);
assert(isset($search) && $search instanceof SearchDTO);
/** @var PageResultDTO<Order> $page */

$template = new Template(
    '_component/_admin_table.php',
    ['page' => $page, 'search' => $search]
);

?>

<?php $template->startFragment('header'); ?>
    <th>Num</th>
    <th>Username</th>
    <th>Ref No</th>
    <th>Quantity</th>
    <th>Price(RM)</th>
    <th>Shipment Status</th>
<?php $template->endFragment(); ?>


<?php $template->start(); ?>
<?php $i = $page->getStartIndex() + 1 ?>
<?php $num = 1; ?>
<?php foreach ($page->items as $order) : ?>
    <tr data-id="<?= $order->id ?>" onclick="window.location=`/admin/order/${this.dataset.id}`">
        <td class="mono"><?= $i++?></td>
        <td><?= $order->user->username ?></td>
        <td><?= $order->invoice->payment->refNo ?></td>
        <td><?= count($order->items) ?></td>
        <td><?= number_format($order->getTotal() / 100, 2) ?></td>
        <td class="chip">
                                <span class="chip"
                                      data-order-status="<?= strtolower($order->getOrderStatus()->name) ?>">
                                    <?= $order->getOrderStatus()->toDescription() ?>
                                </span>
        </td>
    </tr>
<?php endforeach; ?>
<?= $template->end() ?>

<?php
