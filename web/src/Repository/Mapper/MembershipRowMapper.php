<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\User\Membership;
use DateTime;
use OutOfBoundsException;
use PDOStatement;
use RuntimeException;

/**
 * @extends RowMapper<Membership>
 */
class MembershipRowMapper extends RowMapper
{
    public const string FROM_DATE = 'fromDate';
    public const string THRU_DATE = 'thruDate';
    public const string CARD_NO = 'cardNo';
    public const string USER = 'user.';

    /**
     * @inheritDoc
     */
    public function map(PDOStatement $stmt): array
    {
        // TODO: Implement map() method.
        throw new RuntimeException('Not Implemented');
    }

    /**
     * @inheritDoc
     */
    public function mapRow(array $row): Membership
    {
        $id = $this->getColumn($row, self::ID);
        assert(is_int($id));

        try {
            $membership = new Membership();
            $membership->id = $id;
            $this->bindProperties($membership, $row);
        } catch (OutOfBoundsException) {
            $membership = new Membership();
            $membership->id = $id;
            $membership->isLazy = true;
        }

        return $membership;
    }

    /**
     * @inheritDoc
     */
    public function bindProperties(mixed $object, array $row): void
    {
        $object->cardNo = $this->getColumn($row, self::CARD_NO);
        $object->fromDate = DateTime::createFromFormat(
            'Y-m-d H:i:s',
            $this->getColumn($row, self::FROM_DATE)
        );
        $object->thruDate = ($v = $this->getColumn($row, self::THRU_DATE))
            ? DateTime::createFromFormat('Y-m-d H:i:s', $v)
            : null;
    }
}
