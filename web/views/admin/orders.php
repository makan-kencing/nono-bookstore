<?php
declare(strict_types=1);

use App\Core\View;
use App\Entity\Order\Order;
use App\Orm\Expr\PageRequest;

/** @var Order[] $orders */
assert(isset($orders) && is_array($orders));
$title = "Orders";
ob_start();
?>

<main>
    <section class="profile-container">
        <div class="profile-card">
                <table class="user-table" id="user-table">
                    <tbody>
                    <tr>
                        <td>
                            <div>
                                <h2>Orders</h2>
                                <?= View::render('_component/_admin_table_controls.php', ['ajaxUrl' => '/api/orderList/search/']) ?>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
    </section>
    <main>
    <script>


        // $("table.user-table tbody tr").click(/** @param {jQuery.Event} e */ (e) => {
        //     window.location = `/admin/order/${e.currentTarget.dataset.id}`;
        // })
    </script>

<?php
$content = ob_get_clean();
include __DIR__ . "/_base.php";
