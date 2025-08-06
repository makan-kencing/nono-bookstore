<?php

declare(strict_types=1);

use App\Entity\User\User;

/** @var User[] $users */

assert(isset($users) && is_array($users));

$title = 'Users';

ob_start();
?>

<h1>Users</h1>

<div>


    <table>
        <thead>
            <tr>
                <th></th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Verified</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user) : ?>
                <tr>
                    <td></td>
                    <td><?= $user->username ?></td>
                    <td><?= $user->email ?> </td>
                    <td><?= $user->role->name ?></td>
                    <td>
                        <i class="fa-solid fa-circle"
                            style="color: <?= $user->isVerified ? 'green' : 'red' ?>;"></i>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();

include __DIR__ . "/_base.php";
