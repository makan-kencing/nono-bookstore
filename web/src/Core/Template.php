<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

class Template
{
    public bool $started = false;
    public array $fragments = [];
    /** @var ?literal-string */
    public ?string $currentFragment = null;
    public ?string $previous = null;

    /**
     * @param string $file
     * @param array<string, mixed> $data
     */
    public function __construct(
        public string $file,
        public array $data = [],
    ) {
    }

    public function start(): void
    {
        if ($this->started)
            throw new RuntimeException('The template is already started. Raw dump: ' . json_encode($this));

        $this->started = true;

        if (ob_get_status())
            $this->previous = ob_get_clean() ?? '';

        ob_start();
    }

    /**
     * @param literal-string $fragment
     * @return void
     */
    public function startFragment(string $fragment): void
    {
        if ($this->currentFragment !== null)
            throw new RuntimeException('A template fragment is already started. Raw dump: ' . json_encode($this));

        $this->currentFragment = $fragment;
    }

    public function endFragment(): void
    {
        if ($this->currentFragment === null)
            throw new RuntimeException('The template fragment is not yet started.');

        $this->data[$this->currentFragment] = ob_get_clean();
        $this->currentFragment = null;

        ob_start();
    }

    /**
     * Stop a file template and echo out.
     * @return string The rendered content
     */
    public function end(): string
    {
        if (!$this->started)
            throw new RuntimeException('The template is not yet started.');

        $this->data['body'] = ob_get_clean();

        try {
            return View::render($this->file, $this->data) ?: '';
        } finally {
            $this->started = false;

            if ($this->previous !== null) {
                ob_start();
                echo $this->previous;
            }
        }
    }
}
