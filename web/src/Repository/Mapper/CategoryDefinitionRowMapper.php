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
readonly class CategoryDefinitionRowMapper extends RowMapper
{
    /**
     * @inheritDoc
     */
    public function map(PDOStatement $stmt, string $prefix = ''): array
    {
        // TODO: Implement map() method.
        throw new RuntimeException('Not Implemented');
    }

    /**
     * @inheritDoc
     */
    public function mapRow(array $row, string $prefix = ''): CategoryDefinition
    {
        $categoryRowMapper = new CategoryRowMapper();

        $categoryDefinition = new CategoryDefinition();

        $categoryDefinition->category = $categoryRowMapper->mapRow($row, prefix: $prefix . 'category.');
        $categoryDefinition->isPrimary = (bool)$row[$prefix . 'isPrimary'];
        $categoryDefinition->comment = $row[$prefix . 'comment'];
        $categoryDefinition->fromDate = DateTime::createFromFormat('Y-m-d H:i:s', $row[$prefix . 'fromDate']);
        $categoryDefinition->thruDate = isset($row[$prefix . 'thruDate'])
            ? DateTime::createFromFormat('Y-m-d H:i:s', $row[$prefix . 'thruDate'])
            : null;

        return $categoryDefinition;
    }
}
