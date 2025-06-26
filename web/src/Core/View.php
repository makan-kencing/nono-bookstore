<?php declare(strict_types=1);

namespace App\Core;

class View
{
    public static function render(string $file, array $data = []) : string
    {
        $path = '../views/' . $file;

        ob_start();
        extract($data);
        include $path;
        return ob_get_clean();
    }
}