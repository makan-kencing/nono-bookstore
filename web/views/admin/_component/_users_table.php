<?php

declare(strict_types=1);

use App\Core\Template;
use App\DTO\Request\SearchDTO;
use App\DTO\Response\PageResultDTO;
use App\Entity\User\User;

assert(isset($page) && $page instanceof PageResultDTO);
assert(isset($search) && $search instanceof SearchDTO);
/** @var PageResultDTO<User> $page */

$template = new Template(
    '_component/_admin_table.php',
    ['page' => $page, 'search' => $search]
);

?>

<?php $template->startFragment('header'); ?>
<th></th>
<th></th>
<th>Username</th>
<th>Email</th>
<th>Role</th>
<th>Verified</th>
<th>Actions</th>
<?php $template->endFragment(); ?>


<?php $template->start(); ?>
<?php $i = $page->getStartIndex() + 1 ?>
<?php foreach ($page->items as $user): ?>
    <tr data-id="<?= $user->id ?>" onclick="window.location = `/admin/user/${this.dataset.id}`">
        <td class="mono"><?= $i++ ?></td>
        <td>
            <?php if ($user->image !== null): ?>
                <img src="<?= $user->image->filepath ?>" alt="<?= $user->image->alt ?>">
            <?php else: ?>
                <img src="" alt="">
            <?php endif; ?>
        </td>
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
<?php endforeach; ?>

<script>
    $("button.delete").click(/** @param {jQuery.Event} e */(e) => {
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
                success: () => {
                    alert("Delete successful.");
                    row.remove();
                }
            }
        );
    })
</script>
<?= $template->end() ?>

