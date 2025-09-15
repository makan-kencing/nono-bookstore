<?php
declare(strict_types=1);

use App\Core\View;
use App\Entity\Order\Order;

assert(isset($orders) && is_array($orders));
/** @var Order[] $orders */
$title = "Orders";
ob_start();
?>

<main>
    <div>
        <aside></aside>
        <section>
            <h2>Orders</h2>
            <?= View::render('_component/_admin_table_controls.php', ['ajaxUrl' => '/api/orderList/search/']) ?>
        </section>
    </div>
<main>

<?php
$content = ob_get_clean();
include __DIR__ . "/_base.php";
