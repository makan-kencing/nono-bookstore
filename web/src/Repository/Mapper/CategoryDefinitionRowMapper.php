<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\Book\Category\CategoryDefinition;
use PDOStatement;
use DateTime;
use RuntimeException;

/**
 * @extends RowMapper<CategoryDefinition>
 */
class CategoryDefinitionRowMapper extends RowMapper
{
    public const string IS_PRIMARY = 'isPrimary';
    public const string COMMENT = 'comment';
    public const string FROM_DATE = 'fromDate';
    public const string THRU_DATE = 'thruDate';
    public const string CATEGORY = 'category.';
    public const string BOOK = 'book.';

    public CategoryRowMapper $categoryRowMapper;
    public BookRowMapper $bookRowMapper;

    public function __construct(string $prefix = '')
    {
        parent::__construct($prefix);
        $this->categoryRowMapper = new CategoryRowMapper($prefix . self::CATEGORY);
        $this->bookRowMapper = new BookRowMapper($prefix . self::BOOK);
    }

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
        $this->categoryRowMapper->mapOneToOne($row, $object->category);
        $this->bookRowMapper->mapOneToOne($row, $object->book);
        $object->isPrimary = $this->getColumn($row, self::IS_PRIMARY);
        $object->comment = $this->getColumn($row, self::COMMENT);
        $object->fromDate = DateTime::createFromFormat(
            'Y-m-d H:i:s',
            $this->getColumn($row, self::FROM_DATE)
        );
        $object->thruDate = ($v = $this->getColumn($row, self::THRU_DATE))
            ? DateTime::createFromFormat('Y-m-d H:i:s', $v)
            : null;
    }
}
