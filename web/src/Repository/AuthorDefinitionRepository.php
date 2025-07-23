<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Book\Author\AuthorDefinition;
use App\Entity\Book\Author\AuthorDefinitionType;

/**
 * @extends Repository<AuthorDefinition>
 */
readonly class AuthorDefinitionRepository extends Repository
{
    /**
     * @inheritDoc
     */
    #[\Override] public function mapRow(mixed $row, string $prefix = ''): AuthorDefinition
    {
        $authorDefinition = new AuthorDefinition();

        $authorDefinition->type = AuthorDefinitionType::{$row[$prefix . 'type']};
        $authorDefinition->comment = $row[$prefix . 'comment'];

        return $authorDefinition;
    }
}
