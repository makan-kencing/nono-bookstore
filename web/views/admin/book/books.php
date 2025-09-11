<?php

declare(strict_types=1);


use App\Core\View;

ob_start();
?>
    <main>

        <form id="search">
            <div>
                <button>+ Add</button>

                <button type="submit">Refresh</button>
            </div>

            <div id="output-table">

            </div>
        </form>

    </main>

    <script>
        let $searchForm = $("form#search");

        $searchForm.submit(/** @param {jQuery.Event} e */ (e) => {
            e.preventDefault();

            reloadTable();
        });

        function reloadTable() {
            let data = new FormData($searchForm[0]);

            $.ajax(
                '/api/books/search?' + new URLSearchParams(data).toString(),
                {
                    method: 'GET',
                    success: function (data) {
                        $("#output-table").html(data);
                    }
                }
            )
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
    </script>
<?php

$title = 'Books';
$content = ob_get_clean();

echo View::render(
    'admin/_base.php',
    ['title' => $title, 'content' => $content]
);
