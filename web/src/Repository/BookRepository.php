<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Book\Book;
use App\Repository\Mapper\BookRowMapper;
use App\Repository\Query\Query;

/**
 * @extends Repository<Book>
 */
readonly class BookRepository extends Repository
{
    /**
     * @param Query<Book> $query
     * @return Book[]
     */
    public function get(Query $query): array
    {
        $stmt = $query->createQuery($this->conn);
        $stmt->execute();

        $rowMapper = new BookRowMapper();
        return $rowMapper->extract($stmt);
    }
}
