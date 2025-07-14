<?php

declare(strict_types=1);

namespace App\Core;

class View
{
    public static string $viewPath = '';

    /**
     * Render a file as php with the given variables and return the file as string.
     *
     * @param string $file The file relative to the $viewPath
     * @param array<string, mixed> $data The variables to replace in the template.
     * @return string The rendered file
     */
    public static function render(string $file, array $data = []): string
    {
        ob_start();
        extract($data);

        include self::$viewPath . '/' . $file;

        $content = ob_get_clean();
        return !$content ? '' : $content;
    }
}
