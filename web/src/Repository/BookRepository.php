<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Book\Author\AuthorDefinition;
use App\Entity\Book\Book;
use App\Entity\Product\Cost;
use App\Entity\Product\Inventory;
use App\Entity\Product\Price;

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

        $book->id = (int)$this->conn->lastInsertId();
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

    public function insertInventory(Inventory $inventory): void
    {
        $stmt = $this->conn->prepare('
            INSERT INTO inventory (book_id, location, quantity)
            VALUES (:book_id, :location, :quantity);
        ');
        $stmt->execute([
            ':book_id' => $inventory->book->id,
            ':location' => $inventory->location->name,
            ':quantity' => $inventory->quantity
        ]);

        $inventory->id = (int)$this->conn->lastInsertId();
    }

    public function updateInventory(Inventory $inventory): void
    {
        $stmt = $this->conn->prepare('
            UPDATE inventory
            SET quantity = :quantity
            WHERE id = :id;
        ');
        $stmt->execute([
            ':id' => $inventory->id,
            ':quantity' => $inventory->quantity
        ]);
    }

    public function insertPrice(Price $price): void
    {
        $stmt = $this->conn->prepare('
            INSERT INTO price (book_id, from_date, amount, comment)
            VALUES (:book_id, :from_date, :amount, :comment);
        ');
        $stmt->execute([
            ':book_id' => $price->book->id,
            ':from_date' => $price->fromDate->format('Y-m-d H:i:s'),
            ':amount' => $price->amount,
            ':comment' => $price->comment
        ]);

        $price->id = (int)$this->conn->lastInsertId();
    }

    public function setNewPrice(Price $price): void
    {
        $stmt = $this->conn->prepare('
            UPDATE price
            SET thru_date = CURRENT_TIMESTAMP
            WHERE thru_date IS NULL;
        ');
        $stmt->execute();

        $this->insertPrice($price);
    }

    public function insertCost(Cost $cost): void
    {
        $stmt = $this->conn->prepare('
            INSERT INTO cost (book_id, from_date, amount, comment)
            VALUES (:book_id, :from_date, :amount, :comment);
        ');
        $stmt->execute([
            ':book_id' => $cost->book->id,
            ':from_date' => $cost->fromDate->format('Y-m-d H:i:s'),
            ':amount' => $cost->amount,
            ':comment' => $cost->comment
        ]);

        $cost->id = (int)$this->conn->lastInsertId();
    }

    public function setNewCost(Cost $cost): void
    {
        $stmt = $this->conn->prepare('
            UPDATE price
            SET thru_date = CURRENT_TIMESTAMP
            WHERE thru_date IS NULL;
        ');
        $stmt->execute();

        $this->insertCost($cost);
    }
}
