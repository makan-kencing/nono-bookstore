<?php
declare(strict_types=1);

use App\Core\View;
use App\Entity\User\User;
use App\Orm\Expr\PageRequest;

assert(isset($users) && is_array($users));
assert(isset($page) && $page instanceof PageRequest);
assert(isset($count) && is_int($count));
/** @var User[] $users */

$title = 'Users';
ob_start();
?>

    <main>
        <div >
            <aside>

            </aside>

            <section>
                <h2>Users</h2>
                <?= View::render('_component/_admin_table_controls.php', ['ajaxUrl' => '/api/user/search/', 'addAction' => '$(\'dialog.add-user\')[0].showModal();']) ?>
            </section>
        </div>
    </main>
    <link rel="stylesheet" href="/static/styles/Admin/users-table.css">


<?= View::render('admin/_component/_add_user_dialog.php'); ?>

    <script>
        $("form#search button#add").click(/** @param {jQuery.Event} e */(e) => {

        });

        $("dialog.add-user > form").submit(/** @param {jQuery.Event} e */(e) => {
            e.preventDefault();

            const data = new FormData(e.target);

            $.ajax(
                '/api/user',
                {
                    method: 'POST',
                    contentType: "application/json",
                    data: JSON.stringify(Object.fromEntries(data.entries())),
                    error: (jqXHR, textStatus, errorThrown) => {
                        console.error(jqXHR, textStatus, errorThrown)
                        switch (jqXHR.status) {
                            case 401:
                                alert("You are not logged. ");
                                break;
                            case 403:
                                alert("You do not have permission to create this user.");
                                break;
                            case 409:
                                alert("username or email is taken .");
                                break;
                            case 422:
                                alert("Your email method error.");
                                break;
                        }
                    },
                    success: (data, textStatus, jqXHR) => {
                        console.log(data, textStatus, jqXHR);
                        e.target.closest('dialog').close();
                        window.location.reload();
                    }
                }
            );
        });
    </script>

<?php
$content = ob_get_clean();
include __DIR__ . "/_base.php";
