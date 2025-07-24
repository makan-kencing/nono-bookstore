<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\Book\Category\Category;
use PDOStatement;

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
        // TODO: map recursive parent

        $category = new Category();

        $category->id = $row[$prefix . 'id'];
        $category->slug = $row[$prefix . 'slug'];
        $category->name = $row[$prefix . 'name'];
        $category->description = $row[$prefix . 'description'];

        return $category;
    }
}
