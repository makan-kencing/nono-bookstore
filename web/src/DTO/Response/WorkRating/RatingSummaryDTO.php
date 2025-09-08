<?php

declare(strict_types=1);

namespace App\DTO\Response\WorkRating;

use App\DTO\Response\ResponseDTO;

readonly class RatingSummaryDTO extends ResponseDTO
{
    public int $total;
    public float $average;

    /**
     * @param array<int, int> $counts
     */
    public function __construct(
        public array $counts,
    ) {
        $total = array_sum($this->counts);

        $average = 0;
        foreach ($this->counts as $star => $count)
            $average += $star * $count;
        $average /= $total;

        $this->average = $average;
        $this->total = $total;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        return [
            'counts' => $this->counts,
            'total' => $this->total,
            'average' => $this->average
        ];
    }
}
