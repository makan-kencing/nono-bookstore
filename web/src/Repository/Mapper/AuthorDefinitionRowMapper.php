<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\Book\Author\AuthorDefinition;
use App\Entity\Book\Author\AuthorDefinitionType;
use PDOStatement;

/**
 * @extends RowMapper<AuthorDefinition>
 */
readonly class AuthorDefinitionRowMapper extends RowMapper
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
        $authorRowMapper = new AuthorRowMapper();

        $authorDefinition = new AuthorDefinition();

        $authorDefinition->author = $authorRowMapper->mapRow($row, prefix: $prefix . 'author.');
        $authorDefinition->type = AuthorDefinitionType::{$row[$prefix . 'type']};
        $authorDefinition->comment = $row[$prefix . 'comment'];

        return $authorDefinition;
    }
}
