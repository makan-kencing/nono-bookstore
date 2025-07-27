<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\Book\Author\AuthorDefinition;
use App\Entity\Book\Author\AuthorDefinitionType;
use PDOStatement;
use RuntimeException;

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

    private AuthorRowMapper $authorRowMapper;
    private BookRowMapper $bookRowMapper;

    public function getAuthorRowMapper(): AuthorRowMapper
    {
        $this->authorRowMapper ??= new AuthorRowMapper($this->prefix . self::AUTHOR);
        return $this->authorRowMapper;
    }

    public function getBookRowMapper(): BookRowMapper
    {
        $this->bookRowMapper ??= new BookRowMapper($this->prefix . self::BOOK);
        return $this->bookRowMapper;
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
        if ($v = $this->getBookRowMapper()->mapRowOrNull($row)) {
            $object->book = $v;
        }
        if ($v = $this->getAuthorRowMapper()->mapRowOrNull($row)) {
            $object->author = $v;
        }
    }
}
