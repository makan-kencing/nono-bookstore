<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Book\Author\AuthorDefinition;
use App\Entity\Book\Book;

readonly class BookRepository extends Repository
{
    public function insert(Book $book): void
    {
        $stmt = $this->conn->prepare('
            INSERT INTO book (work_id, isbn, description, publisher, publication_date, number_of_pages, cover_type, edition_information, language, dimensions)
            VALUES (:work_id, :isbn, :description, :publisher, :publication_date, :number_of_pages, :cover_type, :edition_information, :language, :dimensions);
        ');
        $stmt->execute([
            ':work_id' => $book->work->id,
            ':isbn' => $book->isbn,
            ':description' => $book->description,
            ':publisher' => $book->publisher,
            ':publication_date' => $book->publicationDate,
            ':number_of_pages' => $book->numberOfPages,
            ':cover_type' => $book->coverType->name,
            ':edition_information' => $book->editionInformation,
            ':language' => $book->language,
            ':dimensions' => $book->dimensions
        ]);

        $book->id = (int) $this->conn->lastInsertId();
    }

    public function update(Book $book): void
    {
        $stmt = $this->conn->prepare('
            UPDATE book
            SET work_id = :work_id,
                isbn = :isbn,
                description = :description,
                publisher = :publisher,
                publication_date = :publication_date,
                number_of_pages = :number_of_pages,
                cover_type = :cover_type,
                edition_information = :edition_information,
                language = :language,
                dimensions = :dimensions
            WHERE id = :id;
        ');
        $stmt->execute([
            ':id' => $book->id,
            ':work_id' => $book->work->id,
            ':isbn' => $book->isbn,
            ':description' => $book->description,
            ':publisher' => $book->publisher,
            ':publication_date' => $book->publicationDate,
            ':number_of_pages' => $book->numberOfPages,
            ':cover_type' => $book->coverType->name,
            ':edition_information' => $book->editionInformation,
            ':language' => $book->language,
            ':dimensions' => $book->dimensions
        ]);
    }

    public function delete(Book|int $book): bool
    {
        if ($book instanceof Book)
            $book = $book->id;

        $stmt = $this->conn->prepare('
            DELETE FROM book
            WHERE id = :id
        ');
        return $stmt->execute([
            ':id' => $book
        ]);
    }

    public function deleteAllBookAuthor(Book|int $book): void
    {
        if ($book instanceof Book)
            $book = $book->id;

        $stmt = $this->conn->prepare('
            DELETE FROM author_definition
            WHERE book_id = :book_id
        ');
        $stmt->execute([
            ':book_id' => $book
        ]);
    }

    public function softDelete(Book|int $book): bool
    {
        $stmt = $this->conn->prepare('
            UPDATE book
            SET deleted_at = CURRENT_TIMESTAMP
            WHERE id = :id
        ');
        return $stmt->execute([
            ':id' => $book
        ]);
    }


    public function insertAuthor(AuthorDefinition $ad): void
    {
        $stmt = $this->conn->prepare('
            INSERT INTO author_definition (book_id, author_id, type)
            VALUES (:book_id, :author_id, :type)
        ');
        $stmt->execute([
            ':book_id' => $ad->book->id,
            ':author_id' => $ad->author->id,
            ':type' => $ad->type?->name
        ]);
    }

    public function updateAuthor(AuthorDefinition $ad): void
    {
        $stmt = $this->conn->prepare('
            UPDATE author_definition
            SET type = :type
            WHERE book_id = :book_id
                AND author_id = :author_id
        ');
        $stmt->execute([
            ':book_id' => $ad->book->id,
            ':author_id' => $ad->author->id,
            ':type' => $ad->type?->name
        ]);
    }

    public function deleteAuthor(AuthorDefinition $ad): void
    {
        $stmt = $this->conn->prepare('
            DELETE FROM author_definition
            WHERE book_id = :book_id
                AND author_id = :author_id
        ');
        $stmt->execute([
            ':book_id' => $ad->book->id,
            ':author_id' => $ad->author->id,
        ]);
    }
}
