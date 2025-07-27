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
readonly class AuthorDefinitionRowMapper extends RowMapper
{
    public const string TYPE = 'type';
    public const string COMMENT = 'comment';
    public const string AUTHOR = 'author.';
    public const string BOOK = 'book.';

    private AuthorRowMapper $authorRowMapper;
    private BookRowMapper $bookRowMapper;

    public function __construct(string $prefix = '')
    {
        parent::__construct($prefix);
        $this->authorRowMapper = new AuthorRowMapper($prefix . self::AUTHOR);
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
        $this->bookRowMapper->mapOneToOne($row, $object->book);
        $this->authorRowMapper->mapOneToOne($row, $object->author);
        $object->type = AuthorDefinitionType::{$this->getColumn($row, self::TYPE)};
        $object->comment = $this->getColumn($row, self::COMMENT);
    }
}
