<?php
declare(strict_types=1);

use App\Entity\Order\Order;

assert(isset($order) && $order instanceof Order);
?>
<style>
    .invoice-container {
        font-family: Arial, sans-serif;
        font-size: 14px;
        color: #333;
        max-width: 600px;
        margin: auto;
        padding: 20px;
        border: 1px solid #eee;
        border-radius: 8px;
    }

    .invoice-container h1 {
        text-align: center;
        color: #444;
    }

    .invoice-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .invoice-table th,
    .invoice-table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    .invoice-table th {
        background: #f4f4f4;
    }

    .invoice-total {
        margin-top: 20px;
        font-weight: bold;
        text-align: right;
    }

    .invoice-footer {
        margin-top: 30px;
        text-align: center;
        color: #888;
    }
</style>
<div class="invoice-container">
    <h1>Invoice #<?= htmlspecialchars($order->refNo) ?></h1>

    <p><strong>Date:</strong> <?= $order->orderedAt->format('Y-m-d') ?></p>
    <p>
        <strong>Customer:</strong> <?= htmlspecialchars($order->user->name) ?><br>
        <strong>Email:</strong> <?= htmlspecialchars($order->user->email) ?>
    </p>

    <table class="invoice-table">
        <thead>
        <tr>
            <th>Item</th>
            <th>Qty</th>
            <th>Unit Price</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($order->items as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item->book->work->title) ?></td>
                <td><?= (int)$item->quantity ?></td>
                <td>RM <?= number_format($item->unitPrice, 2) ?></td>
                <td>RM <?= number_format($item->unitPrice * $item->quantity, 2) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <p class="invoice-total">
        Total Paid: RM <?= number_format($order->invoice->payment->amount / 100, 2) ?>
    </p>

    <p class="invoice-footer">
        Thank you for your purchase!<br>
        <small>This is an automated invoice. Please keep it for your records.</small>
    </p>
</div>
