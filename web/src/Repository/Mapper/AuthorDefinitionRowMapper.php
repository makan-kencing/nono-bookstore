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
    private AuthorRowMapper $authorRowMapper;

    public function __construct()
    {
        $this->authorRowMapper = new AuthorRowMapper();
    }

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
    public function mapRow(array $row, string $prefix = ''): ?AuthorDefinition
    {
        $authorDefinition = new AuthorDefinition();

        $authorDefinition->author = $this->authorRowMapper->mapRow($row, prefix: $prefix . 'author.');
        $authorDefinition->type = AuthorDefinitionType::{$row[$prefix . 'type']};
        $authorDefinition->comment = $row[$prefix . 'comment'];

        return $authorDefinition;
    }
}
