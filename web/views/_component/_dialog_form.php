<?php

declare(strict_types=1);

// params
$id = $id ?? '';
$classes = $classes ?? [];
$title = $title ?? '';

// fragments
$scripts = $scripts ?? '';

assert(isset($body) && is_string($body));

/**
 * @var literal-string $id
 * @var literal-string[] $classes
 * @var string $title
 * @var string $scripts
 */

?>

<dialog id="<?= $id ?>" class="<?= implode(' ', $classes)?>">
    <form method="dialog" style="width: 840px;">
        <div style="display: flex; gap: 1rem;">
            <h2><?= $title ?></h2>

            <button type="reset" style="margin-left: auto">X</button>
        </div>

        <div style="overflow-y: auto; height: 80vh;">
            <?= $body ?>
        </div>

        <div style="display: flex; justify-content: right; gap: 1rem;">
            <button type="reset">Cancel</button>
            <button type="submit">Submit</button>
        </div>
    </form>
</dialog>

<script>
    $("dialog button[type=reset]").click(/** @param {jQuery.Event} e */ function (e) {
        this.closest("dialog").close();
    });
</script>

<?= $scripts ?>
