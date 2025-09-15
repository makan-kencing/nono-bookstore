<?php

declare(strict_types=1);

use App\Core\Template;
use App\Entity\User\UserRole;
use App\Exception\UnauthorizedException;
use App\Router\AuthRule;
use App\Service\AuthService;

// params
$id = $id ?? '';
$classes = $classes ?? [];

$context = AuthService::getLoginContext();
if ($context === null)
    throw new UnauthorizedException();

/**
 * @var literal-string $id
 * @var literal-string[] $classes
 */

$dialog = new Template(
    '_component/_dialog_form.php',
    ['title' => 'Add New User', 'id' => $id, 'classes' => array_merge(['add-user'], $classes)]
);

?>

<?php $dialog->start(); ?>

<div>
    <label for="username">Username</label>
    <input type="text" id="username" name="username">
</div>

<div>
    <label for="email">Email</label>
    <input type="text" id="email" name="email">
</div>

<div>
    <label for="password">Password</label>
    <input type="text" id="password" name="password">
</div>

<div>
    <label for="role">Role</label>
    <select id="role" name="role">
        <?php foreach (UserRole::cases() as $role): ?>
            <?php if (!AuthRule::HIGHER->check($context->role, $role)) continue; ?>
            <option value="<?= $role->name ?>"><?= $role->title() ?></option>
        <?php endforeach; ?>
    </select>
</div>

<?= $dialog->end() ?>
