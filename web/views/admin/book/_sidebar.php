<?php

declare(strict_types=1);

assert(isset($currentMenu) && is_int($currentMenu));

$menus = [
//    ['Works', '/admin/works'],
    ['Books', '/admin/books'],
//    ['Authors', '/admin/authors'],
//    ['Series', '/admin/series'],
//    ['Categories', '/admin/categories']
]

?>

<aside>
    <nav>
        <ul style="display: flex; flex-flow: column; width: max-content">
            <?php $i = 0 ?>
            <?php foreach ($menus as $menu): ?>
                <li>
                    <a href="<?= $menu[1] ?>"
                        <?php if ($i++ === $currentMenu): ?>
                            style="color: blue"
                        <?php endif; ?>>
                        <?= $menu[0] ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
</aside>


