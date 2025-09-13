<?php

declare(strict_types=1);

use App\Core\View;

$title = 'Books';
ob_start();
?>
    <main>
        <div style="display: flex">
            <div>
                <?= View::render('admin/book/_sidebar.php', ['currentMenu' => 1]) ?>
            </div>

            <div>
                <h2>Books</h2>

                <?= View::render('_component/_admin_table_controls.php', ['ajaxUrl' => '/api/book/search/']) ?>
            </div>
        </div>
    </main>

<?= View::render('admin/book/_add_book_dialog.php') ?>

    <script>
        $("form#search button#add").click(/** @param {jQuery.Event} e */(e) => {
            $("dialog.book")[0].showModal();
        });

        $("dialog.book form").submit(/** @param {jQuery.Event} e */ function (e) {
            e.preventDefault();

            $.ajax(
                '/api/book',
                {
                    method: 'POST',
                    data: $(this).serialize(),
                    success: () => {
                        this.closest("dialog").close();
                    },
                    error: (jqXHR, textStatus, errorThrown) => {

                    }
                }
            );
        });
    </script>
<?php

$content = ob_get_clean();

echo View::render(
    'admin/_base.php',
    ['title' => $title, 'content' => $content]
);
