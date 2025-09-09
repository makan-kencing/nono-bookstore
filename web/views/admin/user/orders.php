<?php

declare(strict_types=1);

use App\Core\View;
use App\Entity\User\User;

assert(isset($user) && $user instanceof User );

ob_start();
?>
    <div style="display: flex; flex-flow: row; ">
        <div>
            <?= View::render('admin/user/_sidebar.php', ['currentMenu' => 'Orders', 'user' => $user]) ?>
        </div>

        <div>

        </div>
    </div>

<?php

$title = 'User Orders';
$content = ob_get_clean();

echo View::render(
    'admin/_base.php',
    ['title' => $title, 'content' => $content]
);


