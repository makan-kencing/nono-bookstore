<?php
declare(strict_types=1);

use App\Core\View;
use App\Entity\User\User;
use App\Orm\Expr\PageRequest;

/** @var User[] $users */
assert(isset($users) && is_array($users));
assert(isset($page) && $page instanceof PageRequest);
assert(isset($count) && is_int($count));

$title = 'Users';
ob_start();
?>

    <main>
        <div>
            <h2>Users</h2>

            <?= View::render('_component/_admin_table_controls.php', ['ajaxUrl' => '/api/user/search/']) ?>
        </div>
    </main>

    <section class="profile-container">
        <div class="profile-card">
            <div class="table-toolbar">
                <button class="add btn btn-primary">+ Add</button>
            </div>

            <div class="table-wrapper">
                <table class="user-table">
                    <thead>
                    <tr>
                        <th>Num</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Verified</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

                <div>
                    Page <?= $page->page ?> of <?= (int) (($count - 1) / $page->pageSize) + 1 ?>
                    Showing <?= min($page->pageSize, count($users)) ?> of <?= $count ?>
                </div>

                <div>
                    <?php if ($page->page > 1 ): ?>
                        <a style="float: left" href="/admin/users?page=<?= $page->page - 1 ?>&page_size=<?= $page->pageSize ?>">Previous</a>
                    <?php endif; ?>

                    <?php if ($count > $page->page * $page->pageSize): ?>
                        <a style="float: right" href="/admin/users?page=<?= $page->page + 1 ?>&page_size=<?= $page->pageSize ?>">Next</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

<?= View::render('admin/_component/_add_user_dialog.php'); ?>

    <script>
        $("form#search button#add").click(/** @param {jQuery.Event} e */ (e) => {
            $("dialog.add-user")[0].showModal();
        });

        $("dialog.add-user > form").submit(/** @param {jQuery.Event} e */ (e) => {
            e.preventDefault();

            const data = new FormData(e.target);

            $.ajax(
                e.target.action,
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
