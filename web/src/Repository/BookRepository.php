<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Book\Book;

readonly class BookRepository extends Repository
{
    public function insert(Book $book): void
    {
        // TODO
        $stmt = $this->conn->prepare('
            INSERT INTO book (work_id, isbn, description, publisher, publication_date, number_of_pages, cover_type, edition_information, language, dimensions)
            VALUES ();
        ');
        $stmt->execute([

        ]);

        $book->id = (int) $this->conn->lastInsertId();
    }
}
