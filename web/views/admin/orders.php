<?php
declare(strict_types=1);

use App\Core\View;
use App\Entity\Order\Order;

/** @var Order[] $orders */
assert(isset($orders) && is_array($orders));
$title = "Orders";
ob_start();
?>

    <main>
    <div>
        <h2>Orders</h2>
        <?= View::render('_component/_admin_table_controls.php', ['ajaxUrl' => '/api/orderList/search/']) ?>
    </div>
    <main>

<?php
$content = ob_get_clean();
include __DIR__ . "/_base.php";
