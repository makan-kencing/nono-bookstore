<?php

declare(strict_types=1);

use App\Core\View;
use App\Entity\User\User;

assert(isset($user) && $user instanceof User );

ob_start();
?>
    <div>
        <div>
            <div>
                <table>
                    <thead>
                    <tr>
                        <th>

                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>

                        </td>
                    </tr>
                    </tbody>>
                </table>
            </div>
        </div>
    </div>


<?php

$title = 'User Order';
$content = ob_get_clean();

echo View::render(
    'admin/_base.php',
    ['title' => $title, 'content' => $content]
);



