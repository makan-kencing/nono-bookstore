<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\Book\Category\Category;
use OutOfBoundsException;
use PDOStatement;
use RuntimeException;

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

    public function __construct(string $prefix = '')
    {
        parent::__construct($prefix);
        // TODO: figure out how to include recursive mapper without crashing the server
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
    public function mapRow(array $row): Category
    {
        $id = $this->getColumn($row, self::ID);
        assert(is_int($id));

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
        // TODO: map parent category
        $object->slug = $this->getColumn($row, self::SLUG);
        $object->name = $this->getColumn($row, self::NAME);
        $object->description = $this->getColumn($row, self::DESCRIPTION);
    }
}
