"use strict";

class SearchTable {
    /**
     *
     * @param {HTMLFormElement} form
     * @param {HTMLElement} output
     * @param {string} ajaxUrl
     */
    constructor(form, output, ajaxUrl) {
        this.form = form;

        $(form).submit(/** @param {jQuery.Event} e */ function (e) {
            e.preventDefault();

            $.ajax(
                ajaxUrl,
                {
                    method: 'GET',
                    data: $(form).serialize(),
                    headers: {
                        "Accept": "text/html"
                    },
                    success: (data) => {
                        $(output).html(data);
                    }
                }
            );
        });

        $(form).find("search input[name=query]").change(/** @param {jQuery.Event} e */ (e) => {
            form.requestSubmit();
        })
    }

    submit() {
        this.form.requestSubmit();
    }

    previousPage() {
        let input = this.form.querySelector("input[name=page]");
        input.value = parseInt(input.value) - 1;

        this.submit();
    }

    nextPage() {
        let input = this.form.querySelector("input[name=page]");
        input.value = parseInt(input.value) + 1;

        this.submit();
    }

    page(page) {
        let input = this.form.querySelector("input[name=page]");
        input.value = page;

        this.submit();
    }
}
