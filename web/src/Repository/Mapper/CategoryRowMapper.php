<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\Book\Category\Category;
use PDOStatement;
use Throwable;

readonly class CategoryRowMapper extends RowMapper
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
    public function mapRow(mixed $row, string $prefix = '')
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
            if (!str_contains($e->getMessage(), 'Undefined array key')) {
                throw $e;
            }

            $category = new Category();
            $category->id = $id;
            $category->isLazy = true;
        }

        return $category;
    }
}
