<?php

declare(strict_types=1);

use App\Entity\Order\Order;


assert(isset($order) && $order instanceof Order);
assert(isset($currentMenu) && is_string($currentMenu));

$menus = [
    ['Order Details', '/admin/order/' . $order->id],
    ['Line Items','/admin/order/' . $order->id . '/lineItems'],
    ['Complete Order','/admin/order/' . $order->id . '/complete'],
]

?>

<nav>
    <ul style="display: flex; flex-flow: column; width: max-content">
        <?php foreach ($menus as $menu): ?>
            <li>
                <a href="<?= $menu[1] ?>"
                    <?php if ($menu[0] === $currentMenu): ?>
                        style="color: blue"
                    <?php endif; ?>
                >
                    <?= $menu[0] ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>



