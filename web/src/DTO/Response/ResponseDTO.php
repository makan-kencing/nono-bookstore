<?php

declare(strict_types=1);

namespace App\DTO\Response;

use App\DTO\DTO;
use JsonSerializable;

abstract readonly class ResponseDTO extends DTO implements JsonSerializable
{
}
