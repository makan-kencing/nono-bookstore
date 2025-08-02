<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\User\Membership;
use DateTime;
use OutOfBoundsException;

/**
 * @extends RowMapper<Membership>
 */
class MembershipRowMapper extends RowMapper
{
    public const string ID = self::USER . UserRowMapper::ID;
    public const string FROM_DATE = 'fromDate';
    public const string THRU_DATE = 'thruDate';
    public const string CARD_NO = 'cardNo';
    public const string USER = 'user.';

    /**
     * @inheritDoc
     */
    public function mapRow(array $row): Membership
    {
        $id = $this->getColumn($row, self::ID);
        if (!is_int($id)) {
            throw new OutOfBoundsException();
        }

        try {
            $membership = new Membership();
            $this->bindProperties($membership, $row);
        } catch (OutOfBoundsException) {
            $membership = new Membership();
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
        $object->user = $this->useMapper(UserRowMapper::class, self::USER)->mapRow($row);
    }
}
