<?php
declare(strict_types=1);

use App\Entity\Order\Order;

assert(isset($order) && $order instanceof Order);
?>

<div style="max-width:600px; margin:auto; padding:20px; border:1px solid #eee; border-radius:8px;">
    <h1 style="text-align:center; color:#444; margin:0 0 20px 0;">Invoice #<?= htmlspecialchars($order->refNo) ?></h1>

    <p style="margin:5px 0;"><strong>Date:</strong> <?= $order->orderedAt->format('Y-m-d') ?></p>
    <p style="margin:5px 0 15px 0;">
        <strong>Customer:</strong> <?= htmlspecialchars($order->user->name) ?><br>
        <strong>Email:</strong> <?= htmlspecialchars($order->user->email) ?>
    </p>

    <table style="width:100%; border-collapse:collapse; margin-top:20px;">
        <thead>
        <tr>
            <th style="border:1px solid #ddd; padding:8px; background:#f4f4f4; text-align:left;">Item</th>
            <th style="border:1px solid #ddd; padding:8px; background:#f4f4f4; text-align:left;">Qty</th>
            <th style="border:1px solid #ddd; padding:8px; background:#f4f4f4; text-align:left;">Unit Price</th>
            <th style="border:1px solid #ddd; padding:8px; background:#f4f4f4; text-align:left;">Total</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($order->items as $item): ?>
            <tr>
                <td style="border:1px solid #ddd; padding:8px;"><?= htmlspecialchars($item->book->work->title) ?></td>
                <td style="border:1px solid #ddd; padding:8px;"><?= (int)$item->quantity ?></td>
                <td style="border:1px solid #ddd; padding:8px;">RM <?= number_format($item->unitPrice, 2) ?></td>
                <td style="border:1px solid #ddd; padding:8px;">RM <?= number_format($item->unitPrice * $item->quantity, 2) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <p style="margin-top:20px; font-weight:bold; text-align:right;">
        Total Paid: RM <?= number_format($order->invoice->payment->amount / 100, 2) ?>
    </p>

    <p style="margin-top:30px; text-align:center; color:#888;">
        Thank you for your purchase!<br>
        <small>This is an automated invoice. Please keep it for your records.</small>
    </p>
</div>
