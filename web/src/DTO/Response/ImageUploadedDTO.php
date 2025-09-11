<?php

declare(strict_types=1);

namespace App\DTO\Response;

use App\Entity\File;
use UnexpectedValueException;

readonly class ImageUploadedDTO extends ResponseDTO
{
    public function __construct(
        public int    $id,
        public string $filepath,
        public ?string $alt
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'filepath' => $this->filepath,
            'alt' => $this->alt
        ];
    }

    public static function fromFile(File $file): self
    {
        return new self(
            $file->id ?? throw new UnexpectedValueException('File id is null'),
            $file->filepath,
            $file->alt
        );
    }
}
