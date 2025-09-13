<?php

declare(strict_types=1);

use App\Core\View;

$title = 'Series';
ob_start();
?>
    <main>
        <div style="display: flex">
            <div>
                <?= View::render('admin/book/_sidebar.php', ['currentMenu' => 3]) ?>
            </div>

            <div>
                <h2>Series</h2>

                <form id="search">
                    <div style="display: flex; align-items: center">
                        <button id="add-book" type="button">+ Add</button>

                        <button type="submit">Refresh</button>

                        <search style="margin-left: auto; ">
                            <label for="query">Searching:</label>
                            <input type="search" name="query" id="query">
                        </search>
                    </div>

                    <div id="output-table">

                    </div>
                </form>

            </div>
        </div>
    </main>

    <script>
        let $searchForm = $("form#search");

        $searchForm.submit(/** @param {jQuery.Event} e */(e) => {
            e.preventDefault();

            reloadTable();
        });

        $("search input[name=query]").change(/** @param {jQuery.Event} e */(e) => {
            reloadTable();
        })

        function reloadTable() {
            $.ajax(
                '/api/book/search/',
                {
                    method: 'GET',
                    data: $searchForm.serialize(),
                    headers: {
                        "Accept": "text/html"
                    },
                    success: (data) => {
                        $("#output-table").html(data);
                    }
                }
            );
        }

        function gotoPreviousPage() {
            let input = $searchForm[0].querySelector("input[name=page]");
            input.value = parseInt(input.value) - 1;

            reloadTable();
        }

        function gotoNextPage() {
            let input = $searchForm[0].querySelector("input[name=page]");
            input.value = parseInt(input.value) + 1;

            reloadTable();
        }

        function gotoPage(page) {
            let input = $searchForm[0].querySelector("input[name=page]");
            input.value = page;

            reloadTable();
        }

        reloadTable();
    </script>

<?php

$content = ob_get_clean();

echo View::render(
    'admin/_base.php',
    ['title' => $title, 'content' => $content]
);
