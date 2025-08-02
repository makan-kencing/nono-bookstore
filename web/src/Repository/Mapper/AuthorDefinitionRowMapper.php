<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\Book\Author\AuthorDefinition;
use App\Entity\Book\Author\AuthorDefinitionType;

/**
 * @extends RowMapper<AuthorDefinition>
 */
class AuthorDefinitionRowMapper extends RowMapper
{
    public const string ID = self::AUTHOR . AuthorRowMapper::ID;
    public const string TYPE = 'type';
    public const string COMMENT = 'comment';
    public const string AUTHOR = 'author.';
    public const string BOOK = 'book.';

    /**
     * @inheritDoc
     */
    public function mapRow(array $row): AuthorDefinition
    {
        $authorDefinition = new AuthorDefinition();

        $this->bindProperties($authorDefinition, $row);

        return $authorDefinition;
    }

    /**
     * @inheritDoc
     */
    public function bindProperties(mixed $object, array $row): void
    {
        $object->type = AuthorDefinitionType::{$this->getColumn($row, self::TYPE)};
        $object->comment = $this->getColumn($row, self::COMMENT);
        if ($v = $this->useMapper(BookRowMapper::class, self::BOOK)->mapRowOrNull($row)) {
            $object->book = $v;
        }
        if ($v = $this->useMapper(AuthorRowMapper::class, self::AUTHOR)->mapRowOrNull($row)) {
            $object->author = $v;
        }
    }
}
