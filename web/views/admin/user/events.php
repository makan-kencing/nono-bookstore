<?php

declare(strict_types=1);

use App\Core\View;
use App\Entity\User\User;

assert(isset($user) && $user instanceof User );

ob_start();
?>
    <div style="display: flex; flex-flow: row; ">
        <div>
            <?= View::render('admin/user/_sidebar.php', ['currentMenu' => 'Security Events', 'user' => $user]) ?>
        </div>

        <div>

        </div>
    </div>

<?php

$title = 'User Events';
$content = ob_get_clean();

echo View::render(
    'admin/_base.php',
    ['title' => $title, 'content' => $content]
);


