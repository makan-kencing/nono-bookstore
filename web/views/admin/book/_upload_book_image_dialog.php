<?php

declare(strict_types=1);

use App\Core\Template;

// params
$id ??= '';
$classes ??= [];

/**
 * @var literal-string $id
 * @var literal-string[] $classes
 */

$dialog = new Template(
    '_component/_dialog_form.php',
    ['title' => 'Upload Image(s)', 'id' => $id, 'classes' => array_merge(['upload-image'], $classes)]
);

?>

<?php $dialog->startFragment('scripts'); ?>
<script>
    const $imageInput = $("dialog.upload-image input[type=file]");
    const $imageLabel = $("dialog.upload-image label");

    $imageInput.change(/** @param {jQuery.Event} e */ function (e) {
        const previews = this.closest("form").querySelector("div#image-preview");
        previews.replaceChildren();

        for (let file of this.files) {
            let preview = document.createElement('div');

            let previewImage = document.createElement('img');
            previewImage.src = URL.createObjectURL(file);

            let previewTitle = document.createElement('div');
            previewTitle.textContent = file.name;

            preview.replaceChildren(previewImage, previewTitle);
            previews.appendChild(preview);
        }
    });

    $imageLabel[0].addEventListener("drop", /** @param {DragEvent} e */ function (e) {
        e.preventDefault();
        e.stopPropagation();

        const input = $imageInput[0];
        const availableFormats = input.accept.split(", ");
        let dt = new DataTransfer();

        [...e.dataTransfer.items].forEach((item, i) => {
            if (item.kind !== "file")
                return;

            const file = item.getAsFile();
            if (availableFormats.includes(file.type))
                dt.items.add(file)
        })

        input.files = dt.files;
        input.dispatchEvent(new Event("change"));
    });

    $imageLabel[0].addEventListener("dragover", /** @param {DragEvent} e */ function (e) {
        e.preventDefault();
        e.stopPropagation();
    })

    $("dialog.upload-image")[0].addEventListener("reset", /** @param {Event} e */ function (e) {
        this.querySelector("div#image-preview").replaceChildren();
    })
</script>

<style>
    dialog.upload-image {
        div#image-preview:not(:empty) + label {
            display: none;
        }
    }
</style>
<?php $dialog->endFragment(); ?>

<?php $dialog->start(); ?>
<input type="file" name="images[]" id="images" accept="image/png, image/jpeg, image/webp" multiple required hidden>

<div id="image-preview"></div>

<label for="images">
    <span style="font-size: 1.2rem; display: block">Drag and drop to upload.</span>
    <span>Click to select file(s)</span>
</label>

<?= $dialog->end() ?>
