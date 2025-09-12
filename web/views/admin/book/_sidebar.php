<?php

declare(strict_types=1);

use App\Entity\Book\Book;

assert(isset($book) && $book instanceof Book);
assert(isset($currentMenu) && is_string($currentMenu));

$menus = [
    ['Book Details', '/admin/book/' . $book->id],
    ['Orders', '/admin/book/' . $book->id . '/orders']
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


