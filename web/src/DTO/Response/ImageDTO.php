<?php

declare(strict_types=1);

namespace App\DTO\Response;

use App\Entity\File;

readonly class ImageDTO extends ResponseDTO
{
    public function __construct(
       public string $filepath,
       public ?string $alt
    ) {
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        return [
            'filepath' => $this->filepath,
            'alt' => $this->alt
        ];
    }

    public static function fromFile(File $file): self
    {
        return new self(
            $file->filepath,
            $file->alt
        );
    }
}
