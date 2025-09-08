<?php

declare(strict_types=1);

use App\Core\View;
use App\Entity\Cart\Cart;

assert(isset($cart) && $cart instanceof Cart);

ob_start();
?>
    <?php xdebug_var_dump($cart) ?>
<?php

$title = 'Shopping Cart';
$content = ob_get_clean();

echo View::render(
    'webstore/_base.php',
    ['title' => $title, 'content' => $content]
);
