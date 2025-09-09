<?php

declare(strict_types=1);

use App\Entity\User\User;

assert(isset($user) && $user instanceof User);
assert(isset($currentMenu) && is_string($currentMenu));

$menus = [
    ['User Details', '/admin/user/' . $user->id],
    ['Addresses', '/admin/user/' . $user->id . '/addresses'],
    ['Orders', '/admin/user/' . $user->id . '/orders'],
    ['Security Events', '/admin/user/' . $user->id . '/events'],
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


