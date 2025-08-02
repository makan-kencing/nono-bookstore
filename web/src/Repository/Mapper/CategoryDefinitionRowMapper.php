<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\Book\Category\CategoryDefinition;
use DateTime;

/**
 * @extends RowMapper<CategoryDefinition>
 */
class CategoryDefinitionRowMapper extends RowMapper
{
    public const string ID = self::CATEGORY . CategoryRowMapper::ID;
    public const string IS_PRIMARY = 'isPrimary';
    public const string COMMENT = 'comment';
    public const string FROM_DATE = 'fromDate';
    public const string THRU_DATE = 'thruDate';
    public const string CATEGORY = 'category.';
    public const string BOOK = 'book.';

    /**
     * @inheritDoc
     */
    public function mapRow(array $row): CategoryDefinition
    {
        $categoryDefinition = new CategoryDefinition();

        $this->bindProperties($categoryDefinition, $row);

        return $categoryDefinition;
    }

    /**
     * @inheritDoc
     */
    public function bindProperties(mixed $object, array $row): void
    {
        $object->isPrimary = (bool)$this->getColumn($row, self::IS_PRIMARY);
        $object->comment = $this->getColumn($row, self::COMMENT);
        $object->fromDate = DateTime::createFromFormat(
            'Y-m-d H:i:s',
            $this->getColumn($row, self::FROM_DATE)
        );
        $object->thruDate = ($v = $this->getColumn($row, self::THRU_DATE))
            ? DateTime::createFromFormat('Y-m-d H:i:s', $v)
            : null;
        if ($v = $this->useMapper(CategoryRowMapper::class, self::CATEGORY)->mapRowOrNull($row)) {
            $object->category = $v;
        }
        if ($v = $this->useMapper(BookRowMapper::class, self::BOOK)->mapRowOrNull($row)) {
            $object->book = $v;
        }
    }
}
