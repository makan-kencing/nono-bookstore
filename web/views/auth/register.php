<?php declare(strict_types=1);

$title = 'Register';

ob_start();
?>
    <div>
        <h2>Register</h2>
        <form id="registration" method="POST" action="/api/user/register">
            <div>
                <label for="username">Username</label>
                <input type="text" id="username" name="username" data-username-taken="0" required>
                <span class="hint" style="color: red">Username is taken</span>

<!--                TODO: move to css file -->
                <style>
                    [data-username-taken='0'] + span.hint {
                        display: none;
                    }
                </style>
            </div>
            <div>
<!--                TODO: check email taken -->
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Register</button>
        </form>
    </div>

    <script>
        $("input#username").change(/** @param {jQuery.Event} e */(e) => {
            $.get(
                "/api/user/username/" + e.target.value,
                (data, textStatus, jqXHR) => {
                    console.log(data, textStatus, jqXHR);

                    if (data.exists)
                        e.target.dataset.usernameTaken = "1";
                    else
                        e.target.dataset.usernameTaken = "0";

                },
                "json"
            )
        });

        $("form#registration").submit(/** @param {jQuery.Event} e */(e) => {
            e.preventDefault();
            console.log(e);

            const data = new FormData(e.target);

            $.ajax(
                e.target.action,
                {
                    method: e.target.method,
                    contentType: "application/json",
                    data: JSON.stringify(Object.fromEntries(data.entries())),
                    error: (jqXHR, textStatus, errorThrown) => {
                        console.error(jqXHR, textStatus, errorThrown)
                    },
                    success: (data, textStatus, jqXHR) => {
                        console.log(data, textStatus, jqXHR);

                        // TODO: notify registration successful

                        // redirect to login after 3 secs
                        setTimeout(() => {
                            window.location.href = "/login";
                        }, 3000);
                    }
                }
            );
        })
    </script>
<?php
$content = ob_get_clean();

include __DIR__ . "/_base.php";
