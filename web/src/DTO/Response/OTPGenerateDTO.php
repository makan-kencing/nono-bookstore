<?php

declare(strict_types=1);

namespace App\DTO\Response;

use App\DTO\Response\ResponseDTO;

readonly class OTPGenerateDTO extends ResponseDTO
{
    public function __construct(
        public string $secret,
        public string $qrUrl
    ) {
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        return [
            'secret' => $this->secret,
            'qr_url' => $this->qrUrl
        ];
    }
}
