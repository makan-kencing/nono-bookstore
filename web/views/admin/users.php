<?php
declare(strict_types=1);

use App\Entity\User\User;
use App\Entity\User\UserRole;
use App\Orm\Expr\PageRequest;

/** @var User[] $users */
assert(isset($users) && is_array($users));
assert(isset($page) && $page instanceof PageRequest);
assert(isset($count) && is_int($count));

$title = 'Users';
ob_start();
?>

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
                    <?php $num = $page->getStartIndex() + 1 ?>
                    <?php foreach ($users as $user): ?>
                        <tr data-id="<?= $user->id ?>">
                            <td class="mono"><?= $num ?></td>
                            <td><?= $user->username ?></td>
                            <td><?= $user->email ?></td>
                            <td><?= $user->role->name ?></td>

                            <td>
                                <?php if ($user->isVerified): ?>
                                    <span class="chip chip-ok">Yes</span>
                                <?php else: ?>
                                    <span class="chip chip-no">No</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <div class="action-group">
                                    <a href="/admin/user/<?= $user->id ?>" class="btn btn-ghost"><i class="fa-solid fa-users"></i></a>
                                    <button class="delete btn btn-danger"><i class="fa-solid fa-user-slash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <?php $num++ ?>
                    <?php endforeach; ?>
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

    <dialog class="add-user">
        <form method="dialog" action="/api/user">
            <div>
                <h3>Add New User</h3>
            </div>
            <div>
                <label for="username">Username</label>
                <input type="text" id="username" name="username">
                <label for="email">Email</label>
                <input type="text" id="email" name="email">
                <label for="password">Password</label>
                <input type="text" id="password" name="password">
                <label for="role">Role</label>
                <select id="role" name="role">
                    <?php foreach (UserRole::cases() as $role): ?>
                    <option value="<?= $role->name ?>"><?= $role->title() ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <button type="reset">Cancel</button>
                <button type="submit">Submit</button>
            </div>
        </form>
    </dialog>

    <script>
        $("table.user-table tbody tr").click(/** @param {jQuery.Event} e */ (e) => {
            window.location = `/admin/user/${e.currentTarget.dataset.id}`;
        })

        $("button.add").click(/** @param {jQuery.Event} e */ (e) => {
            $("dialog.add-user")[0].showModal();
        });

        $("dialog button[type=reset]").click(/** @param {jQuery.Event} e */ (e) => {
            e.target.closest('dialog').close();
        })

        $("dialog.add-user > form").submit(/** @param {jQuery.Event} e */ (e) => {
            e.stopPropagation();

            const data = new FormData(e.target);

            $.ajax(
                e.target.action,
                {
                    method: 'POST',
                    contentType: "application/json",
                    data: JSON.stringify(Object.fromEntries(data.entries())),
                    error: (jqXHR, textStatus, errorThrown) => {
                        console.error(jqXHR, textStatus, errorThrown)
                    },
                    success: (data, textStatus, jqXHR) => {
                        console.log(data, textStatus, jqXHR);

                        e.target.closest('dialog').close();
                    }
                }
            );

        });


        $("button.delete").click(/** @param {jQuery.Event} e */ (e) => {
            e.stopPropagation();

            const row = e.target.closest("tr");

            const id = row.dataset.id;

            if (!confirm(`Delete user with "${id}"?`)) return;

            $.ajax(
                `/api/user/${id}`,
                {
                    method: "DELETE",
                    error: (jqXHR, textStatus, errorThrown) => {
                        console.error(jqXHR, textStatus, errorThrown);

                        switch (jqXHR.status) {
                            case 401:
                                alert("You are not logged. ");
                                break;
                            case 403:
                                alert("You do not have permission to delete this user.");
                                break;
                            case 409:
                                alert("You cannot delete the user as it is referenced in other places.");
                                break;
                            default:
                                alert("Delete failed.");
                        }
                    },
                    success: (data, textStatus, jqXHR) => {
                        row.remove();
                    }
                }
            )
        })
    </script>

<?php
$content = ob_get_clean();
include __DIR__ . "/_base.php";
