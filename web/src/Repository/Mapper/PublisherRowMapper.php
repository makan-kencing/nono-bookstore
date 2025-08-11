<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\Book\Publisher;
use OutOfBoundsException;

/**
 * @extends RowMapper<Publisher>
 */
class PublisherRowMapper extends RowMapper
{
    public const string SLUG = 'slug';
    public const string NAME = 'name';
    public const string BOOKS = 'books.';

    /**
     * @inheritDoc
     */
    public function mapRow(array $row): Publisher
    {
        $id = $this->getColumn($row, self::ID);
        if (!is_int($id)) {
            throw new OutOfBoundsException();
        }

        try {
            $publisher = new Publisher();
            $publisher->id = $id;
            $this->bindProperties($publisher, $row);
        } catch (OutOfBoundsException) {
            $publisher = new Publisher();
            $publisher->id = $id;
            $publisher->isLazy = true;
        }

        return $publisher;
    }

    /**
     * @inheritDoc
     */
    public function bindProperties(mixed $object, array $row): void
    {
        $object->slug = $this->getColumn($row, self::SLUG);
        $object->name = $this->getColumn($row, self::NAME);
    }
}
