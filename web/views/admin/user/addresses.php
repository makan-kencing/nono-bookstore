<?php

declare(strict_types=1);

use App\Core\View;
use App\Entity\User\User;

assert(isset($user) && $user instanceof User);

ob_start();
?>

    <main>
        <div>
            <aside>
                <?= View::render('admin/user/_sidebar.php', ['currentMenu' => 'Addresses', 'user' => $user]) ?>
            </aside>
            <section style="display: grid; grid-template-columns: 1fr 1fr;">
                <?php foreach ($user->addresses as $addr): ?>
                    <form class="form-group">
                        <h3>Address Information</h3>

                        <label for="address1">Address 1</label>
                        <input type="text" id="address1" name="address1" value="<?= $addr->address1 ?>">

                        <label for="address2">Address 2</label>
                        <input type="text" id="address2" name="address2" value="<?= $addr->address2 ?>">

                        <label for="state">State</label>
                        <input type="text" id="state" name="state" value="<?= $addr->state ?>">

                        <div class="form-row">
                            <div>
                                <label for="postcode">Postcode</label>
                                <input type="text" id="postcode" name="postcode" value="<?= $addr->postcode ?>">
                            </div>
                            <div>
                                <label for="country">Country</label>
                                <input type="text" id="country" name="country" value="<?= $addr->country ?>">
                            </div>
                        </div>
                    </form>
                <?php endforeach ?>
            </section>
        </div>
    </main>

<?php

$title = 'User Addresses';
$content = ob_get_clean();

echo View::render(
    'admin/_base.php',
    ['title' => $title, 'content' => $content]
);


