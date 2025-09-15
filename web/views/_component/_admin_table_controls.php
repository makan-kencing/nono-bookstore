<?php

declare(strict_types=1);

// params
$addAction ??= '';
assert(isset($ajaxUrl) && is_string($ajaxUrl));
assert(is_string($addAction));

?>

<form id="search">
    <div class="controls">
        <?php if ($addAction): ?>
            <button type="button" onclick="<?= $addAction ?>">
                <i class="fa-solid fa-plus"></i> Add
            </button>
        <?php endif; ?>

        <search>
            <label for="query">Searching:</label>
            <input type="search" name="query" id="query">
        </search>

        <button title="Refresh" type="submit">
            <i class="fa-solid fa-rotate"></i>
        </button>
    </div>

    <div id="output-table">

    </div>
</form>

<script>
    table = new SearchTable($("form#search")[0], $("#output-table")[0], "<?= $ajaxUrl ?>");
    table.submit();
</script>
