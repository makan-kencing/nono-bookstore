<?php

declare(strict_types=1);

namespace App\Repository\Mapper;

use App\Entity\File;
use DateTime;
use OutOfBoundsException;

/**
 * @extends RowMapper<File>
 */
class FileRowMapper extends RowMapper
{
    public const string FILENAME = 'filename';
    public const string MIMETYPE = 'mimetype';
    public const string ALT = 'alt';
    public const string FILEPATH = 'filepath';
    public const string CREATED_AT = 'createdAt';
    public const string CREATED_BY = 'createdBy.';

    /**
     * @inheritDoc
     */
    public function mapRow(array $row): File
    {
        $id = $this->getColumn($row, self::ID);
        if (!is_int($id)) {
            throw new OutOfBoundsException();
        }

        try {
            $file = new File();
            $file->id = $id;
            $this->bindProperties($file, $row);
        } catch (OutOfBoundsException) {
            $file = new File();
            $file->id = $id;
            $file->isLazy = true;
        }

        return $file;
    }

    /**
     * @inheritDoc
     */
    public function bindProperties(mixed $object, array $row): void
    {
        $object->filename = $this->getColumn($row, self::FILENAME);
        $object->mimetype = $this->getColumn($row, self::MIMETYPE);
        $object->alt = $this->getColumn($row, self::ALT);
        $object->filepath = $this->getColumn($row, self::FILEPATH);
        $object->createdAt = DateTime::createFromFormat(
            'Y-m-d H:i:s',
            $this->getColumn($row, self::CREATED_AT)
        );
        $object->createdBy = $this->useMapper(UserRowMapper::class, self::CREATED_BY)->mapRow($row);
    }
}
