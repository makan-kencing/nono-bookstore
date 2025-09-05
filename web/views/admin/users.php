<?php
declare(strict_types=1);

use App\Entity\User\User;

/** @var User[] $users */
assert(isset($users) && is_array($users));

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
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Verified</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody><?php $num=1;?>
                    <?php foreach ($users as $user): ?>
                        <tr data-id="<?= $user->id ?>">
                            <td class="mono"><?= $num ?></td>
                            <td><?= $user->username ?></td>
                            <td><?= $user->email ?></td>
                            <td><?= $user->role->name ?></td>
                            <td>
                                <?php if (!empty($user->isVerified)): ?>
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
                        <?php $num++;?>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <dialog class="add-user">
        <form method="dialog">
            <div>
                <label for="username">Username</label>
                <input type="text" id="username" name="username">
            </div>

            <div>
                <button type="reset">Cancel</button>
                <button type="submit">Submit</button>
            </div>
        </form>
    </dialog>

    <script>
        $("button.add").click(/** @param {jQuery.Event} e */ (e) => {
            $("dialog.add-user")[0].showModal();
        });

        $("dialog.add-user > form").submit(/** @param {jQuery.Event} e */ (e) => {

            // Do ajax stuff

        });


        $("button.delete").click(/** @param {jQuery.Event} e */ (e) => {
            const row = e.target.closest("tr");

            const id = row.dataset.id;

            if (!confirm(`Delete user with "${id}"?`)) return;

            $.ajax(
                `/api/user/${id}`,
                {
                    method: "DELETE",
                    error: (jqXHR, textStatus, errorThrown) => {
                        console.error(jqXHR, textStatus, errorThrown);
                        alert("Delete failed");
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
