<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\Book\Category\Category;
use OutOfBoundsException;

/**
 * @extends RowMapper<Category>
 */
class CategoryRowMapper extends RowMapper
{
    public const string SLUG = 'slug';
    public const string NAME = 'name';
    public const string DESCRIPTION = 'description';
    public const string PARENT = 'parent.';
    public const string SUBCATEGORIES = 'subcategories.';

    /**
     * @inheritDoc
     */
    public function mapRow(array $row): Category
    {
        $id = $this->getColumn($row, self::ID);
        if (!is_int($id)) {
            throw new OutOfBoundsException();
        }

        try {
            $category = new Category();
            $category->id = $id;
            $this->bindProperties($category, $row);
        } catch (OutOfBoundsException) {
            $category = new Category();
            $category->id = $id;
            $category->isLazy = true;
        }

        return $category;
    }

    /**
     * @inheritDoc
     */
    public function bindProperties(mixed $object, array $row): void
    {
        $object->slug = $this->getColumn($row, self::SLUG);
        $object->name = $this->getColumn($row, self::NAME);
        $object->description = $this->getColumn($row, self::DESCRIPTION);
        $object->parent = $this->useMapper(CategoryRowMapper::class, self::PARENT)->mapRowOrNull($row);
    }
}
