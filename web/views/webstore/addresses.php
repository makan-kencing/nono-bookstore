<?php

declare(strict_types=1);

use App\Core\Template;
use App\Core\View;
use App\Entity\User\User;

assert(isset($user) && $user instanceof User);

$template = new Template(
    'webstore/_base.php',
    ['title' => 'Addresses']
);

$addressDialog = new Template(
    '_component/_dialog_form.php',
    ['title' => 'Address', 'classes' => ['address']]
)

?>

<?php $template->start(); ?>
<main>
    <!-- right side -->
    <div style="border: 1px gray solid; width: 100%;">
        <!-- header -->
        <div style="display: flex; align-items: center; padding: 1rem;">
            <h2 style="margin-right: auto">My Addresses</h2>

            <button id="add-address" style="padding: 0.3rem 1rem;">+ Add New Address</button>
        </div>

        <!-- address section -->
        <div style="padding: 0.5rem 1rem;">
            <h3>Address</h3>

            <!-- addresses -->
            <div style="margin-top: 1rem; display: flex; flex-flow: column; gap: 1rem; align-items: stretch">
                <?php foreach ($user->addresses as $address) : ?>
                    <?php $isDefault = $address->id === $user->defaultAddress?->id ?>
                    <form style="display: flex; gap: 1rem;">
                        <input type="hidden" name="id" value="<?= $address->id ?>">
                        <input type="hidden" name="name" value="<?= $address->name ?>">
                        <input type="hidden" name="phone_number" value="<?= $address->phoneNumber ?>">
                        <input type="hidden" name="address1" value="<?= $address->address1 ?>">
                        <input type="hidden" name="address2" value="<?= $address->address2 ?? '' ?>">
                        <input type="hidden" name="address3" value="<?= $address->address3 ?? '' ?>">
                        <input type="hidden" name="state" value="<?= $address->state ?>">
                        <input type="hidden" name="postcode" value="<?= $address->postcode ?>">
                        <input type="hidden" name="country" value="<?= $address->country?>">
                        <input type="hidden" name="default" value="<?= $isDefault ? 1 : 0 ?>">

                        <!-- left -->
                        <div>
                            <h4><?= $address->name ?></h4> | <?= $address->phoneNumber ?>
                            <p><?= implode(', ', [$address->address1, $address->address2, $address->address3]) ?></p>
                            <p><?= implode(', ', [$address->state, $address->postcode, $address->country]) ?></p>

                            <?php if ($isDefault): ?>
                                <p>Default</p>
                            <?php endif; ?>
                        </div>

                        <!-- right -->
                        <div style="margin-left: auto; ">
                            <div>
                                <button type="button" id="edit-address">Edit</button>

                                <?php if (!$isDefault): ?>
                                    <button type="button" id="delete-address">Delete</button>
                                <?php endif; ?>
                            </div>

                            <?php if (!$isDefault): ?>
                                <button type="button" id="set-default-address">Set as default</button>
                            <?php endif; ?>
                        </div>
                    </form>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>


<?php $addressDialog->start(); ?>
<input type="hidden" name="id">

<div class="field">
    <label for="name">Full name</label>
    <input type="text" name="name" id="name" required>
</div>

<div class="field">
    <label for="phone-number">Phone number</label>
    <input type="tel" name="phone_number" id="phone-number" required>
</div>

<div class="field">
    <label for="address">Address</label>
    <input type="text" name="address1" id="address" autocomplete="address-line1" required>
    <input type="text" name="address2" id="address" autocomplete="address-line2">
    <input type="text" name="address3" id="address" autocomplete="address-line3">
</div>

<div class="field-group">
    <div class="field">
        <label for="state">State</label>
        <input type="text" name="state" id="state" autocomplete="state" required>
    </div>

    <div class="field">
        <label for="postcode">Postcode</label>
        <input type="text" name="postcode" id="postcode" autocomplete="postal-code" pattern="[\d\-]+" required>
    </div>

    <div class="field">
        <label for="country">Country</label>
        <input type="text" name="country" id="country" autocomplete="country" required>
    </div>
</div>

<label class="default">
    <input type="checkbox" name="default">
    Make this my default address
</label>
<?= $addressDialog->end() ?>


<style>
    dialog.address form {
        > div.content {
            gap: 1rem;

            div.field {
                display: flex;
                flex-flow: column;
                align-items: stretch;
                gap: 0.2rem;
            }

            div.field-group {
                display: flex;
                width: 100%;
                gap: 1rem;
            }
        }

        &[data-type='edit'] label.default {
            display: none;
        }
    }
</style>

<script>
    $("button#add-address").click(/** @param {jQuery.Event} e */ function (e) {
        const $dialog = $("dialog.address");

        const form = $dialog.find("form");
        form[0].dataset.type = "add";

        const title = $dialog.find("form > div:first-child > h2");
        title.text("Add Address");

        form.find("input")
            .val("")
            .prop("checked", false);

        $dialog[0].showModal();
    });

    $("button#edit-address").click(/** @param {jQuery.Event} e */ function (e) {
        const data = new FormData(e.target.closest("form"));

        const $dialog = $("dialog.address");

        const form = $dialog.find("form");
        form[0].dataset.type = "edit";

        const title = $dialog.find("form > div:first-child > h2");
        title.text("Edit Address");

        for (let [key, value] of data.entries()) {
            const input = form.find(`input[name=${key}]`);

            if (input[0].type === "checkbox")
                input.prop("checked", value === "1");
            else
                input.val(value);
        }

        $dialog[0].showModal();
    });

    $("button#set-default-address").click(/** @param {jQuery.Event} e */ function (e) {
        data = new FormData(e.target.closest("form"));

        $.ajax(
            `/api/address/${data.get("id")}/default`,
            {
                method: "PUT",
                success: () => {
                    window.location.reload();
                },
                error: (xhr) => {

                }
            }
        );
    });

    $("dialog.address form").submit(/** @param {jQuery.Event} e */ function (e) {
        e.preventDefault();

        const data = new FormData(e.target);

        let url, method;
        if (e.target.dataset.type === "add") {
            url = "/api/address";
            method = "POST";
        } else if (e.target.dataset.type === "edit") {
            url = `/api/address/${data.get("id")}`;
            method = "PUT";
        }

        $.ajax(
            url,
            {
                method: method,
                data: JSON.stringify(Object.fromEntries(data.entries())),
                success: () => {
                    window.location.reload();
                },
                error: (xhr) => {

                }
            }
        );
    });


</script>


<?= $template->end() ?>






