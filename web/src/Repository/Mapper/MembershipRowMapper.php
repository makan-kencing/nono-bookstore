<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\User\Membership;
use DateTime;
use PDOStatement;
use Throwable;

/**
 * @extends RowMapper<Membership>
 */
readonly class MembershipRowMapper extends RowMapper
{
    /**
     * @inheritDoc
     */
    public function map(PDOStatement $stmt, string $prefix = '')
    {
        // TODO: Implement map() method.
    }

    /**
     * @inheritDoc
     */
    public function mapRow(array $row, string $prefix = '')
    {
        $id = $row[$prefix . 'id'] ?? null;
        if ($id == null) {
            return null;
        }

        $membership = new Membership();
        $membership->id = $id;
        try {
            $membership->cardNo = $row[$prefix . 'cardNo'];
            $membership->fromDate = DateTime::createFromFormat('Y-m-d H:i:s', $row[$prefix . 'fromDate']);
            $membership->thruDate = isset($row[$prefix . 'thruDate'])
                ? DateTime::createFromFormat('Y-m-d H:i:s', $row[$prefix . 'thruDate'])
                : null;
        } catch (Throwable $e) {
            if (!$this->isInvalidArrayAccess($e)) {
                throw $e;
            }

            $membership = new Membership();
            $membership->id = $id;
            $membership->isLazy = true;
        }

        return $membership;
    }
}
