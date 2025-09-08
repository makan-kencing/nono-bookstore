<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\Response\WorkRating\RatingSummaryDTO;
use PDO;

readonly class RatingRepository extends Repository
{
    public function getRatingSummary(int $workId): RatingSummaryDTO
    {
        $stmt = $this->conn->prepare('
            SELECT rating, COUNT(*)
            FROM rating
            WHERE work_id = :work_id
            GROUP BY rating
        ');
        $stmt->bindValue(':work_id', $workId);
        $stmt->execute();

        $counts = [];
        foreach ($stmt->fetchAll(PDO::FETCH_NUM) as $row)
            $counts[$row[0]] = $row[1];

        for ($score = 1; $score <= 10; $score++)
            $counts[$score] ??= 0;

        return new RatingSummaryDTO($counts);
    }
}
