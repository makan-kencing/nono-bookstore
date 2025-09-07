<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Controller\Controller;

abstract readonly class WebController extends Controller
{
    /**
     * @param string $file
     * @param array<string, mixed> $data
     * @return string
     */
    public function render(string $file, array $data = []): string
    {
        return $this->view->render($file, $data);
    }

}
