<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\File;

readonly class FileRepository extends Repository
{
    public function insert(File $file): void
    {
        $stmt = $this->conn->prepare('
            INSERT INTO file (user_id, filename, mimetype, alt, filepath, local_filepath, created_at)
            VALUES (:user_id, :filename, :mimetype, :alt, :filepath, :local_filepath, :created_at)
        ');

        $stmt->execute([
            ':user_id' => $file->user->id,
            ':filename' => $file->filename,
            ':mimetype' => $file->mimetype,
            ':filepath' => $file->filepath,
            ':local_filepath' => $file->localFilepath,
            ':alt' => $file->alt,
            ':created_at' => $file->createdAt->format('Y-m-d H:i:s')
        ]);

        $file->id = (int) $this->conn->lastInsertId();
    }

    public function update(File $file): void
    {
        $stmt = $this->conn->prepare('
            UPDATE file
            SET filename = :filename,
                mimetype = :mimetype,
                alt = :alt,
                filepath = :filepath,
                local_filepath = :local_filepath
            WHERE id = :id
        ');

        $stmt->execute([
            ':id' => $file->id,
            ':filename' => $file->filename,
            'mimetype' => $file->mimetype,
            'filepath' => $file->filepath,
            'local_filepath' => $file->localFilepath,
            'alt' => $file->alt
        ]);
    }

    public function deleteById(int $id): void
    {
        $stmt = $this->conn->prepare('DELETE FROM file WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }
}
