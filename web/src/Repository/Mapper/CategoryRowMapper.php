<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\Book\Category\Category;
use PDOStatement;
use RuntimeException;
use Throwable;

/**
 * @extends RowMapper<Category>
 */
readonly class CategoryRowMapper extends RowMapper
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
    public function mapRow(array $row, string $prefix = ''): ?Category
    {
        $id = $row[$prefix . 'id'] ?? null;
        if ($id == null) {
            return null;
        }

        $category = new Category();
        $category->id = $id;
        try {
            $category->slug = $row[$prefix . 'slug'];
            $category->name = $row[$prefix . 'name'];
            $category->description = $row[$prefix . 'description'];
            $category->parent = $this->mapRow($row, prefix: $prefix . 'parent.');
        } catch (Throwable $e) {
            if (!$this->isInvalidArrayAccess($e)) {
                throw $e;
            }

            $category = new Category();
            $category->id = $id;
            $category->isLazy = true;
        }

        return $category;
    }
}
