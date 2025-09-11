<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\File;
use DateTime;
use PDO;

readonly class FileRepository extends Repository
{
    public function insert(File $file): void
    {
        $stmt = $this->conn->prepare('
            INSERT INTO file (user_id, filename, mimetype, alt, filepath, created_at)
            VALUES (:user_id, :filename, :mimetype, :alt, :filepath, :created_at)
        ');

        $stmt->bindValue(':user_id', $file->user->id);
        $stmt->bindValue(':filename', $file->filename);
        $stmt->bindValue(':mimetype', $file->mimetype);
        $stmt->bindValue(':alt', $file->alt);
        $stmt->bindValue(':filepath', $file->filepath);
        $stmt->bindValue(':created_at', $file->createdAt->format('Y-m-d H:i:s'));

        $stmt->execute();

        // set ID back on entity
        $file->id = (int) $this->conn->lastInsertId();
    }

    public function update(File $file): void
    {
        $stmt = $this->conn->prepare('
            UPDATE file
            SET filename = :filename,
                mimetype = :mimetype,
                alt = :alt,
                filepath = :filepath
            WHERE id = :id
        ');

        $stmt->bindValue(':id', $file->id);
        $stmt->bindValue(':filename', $file->filename);
        $stmt->bindValue(':mimetype', $file->mimetype);
        $stmt->bindValue(':alt', $file->alt);
        $stmt->bindValue(':filepath', $file->filepath);

        $stmt->execute();
    }

    public function deleteById(int $id): void
    {
        $stmt = $this->conn->prepare('DELETE FROM file WHERE id = :id');
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }

    public function findByUserId(int $userId): ?File
    {
        $stmt = $this->conn->prepare('SELECT * FROM file WHERE user_id = :user_id LIMIT 1');
        $stmt->bindValue(':user_id', $userId);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        $file = new File();
        $file->id = (int) $row['id'];
        $file->filename = $row['filename'];
        $file->mimetype = $row['mimetype'];
        $file->alt = $row['alt'];
        $file->filepath = $row['filepath'];
        $file->createdAt = new DateTime($row['created_at']);

        return $file;
    }
}
