<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\File;
use App\Exception\BadRequestException;
use App\Exception\ConflictException;
use App\Exception\ContentTooLargeException;
use App\Exception\UnauthorizedException;
use App\Exception\UnprocessableEntityException;
use App\Repository\FileRepository;
use DateTime;
use PDO;
use RuntimeException;

/**
 * @phpstan-type PhpFile array{name: string, full_path: string, type: string, tmp_name: string, error: int, size: int}
 */
readonly class FileService extends Service
{
    public const ACCEPTABLE_IMAGES = [
        'image/jpeg',
        'image/png',
        'image/webp'
    ];

    private FileRepository $fileRepository;
    private string $uploadDir;

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        $this->fileRepository = new FileRepository($pdo);
        $this->uploadDir = getenv('APP_UPLOAD_DIR');
    }

    /**
     * @param PhpFile $file
     * @return File
     * @throws UnauthorizedException
     * @throws UnprocessableEntityException
     * @throws ConflictException
     * @throws ContentTooLargeException
     * @throws BadRequestException
     */
    public function uploadImage(array $file): File
    {
        $context = $this->getSessionContext();
        if ($context === null)
            throw new UnauthorizedException();

        switch ($file['error']) {
            case UPLOAD_ERR_INI_SIZE:
                throw new ContentTooLargeException(['message' => 'Maximum allowed upload size is ' . ini_get('upload_max_filesize')]);
            case UPLOAD_ERR_FORM_SIZE:
                throw new ContentTooLargeException(['message' => 'Maximum number of uploads is ' . ini_get('max_file_uploads')]);
            case UPLOAD_ERR_PARTIAL:
                throw new UnprocessableEntityException(['message' => 'The uploaded file was only partially uploaded']);
            case UPLOAD_ERR_NO_FILE:
                throw new BadRequestException(['message' => 'No file was uploaded']);
            case UPLOAD_ERR_NO_TMP_DIR:
                throw new RuntimeException('Missing a temp folder');
            case UPLOAD_ERR_CANT_WRITE:
                throw new RuntimeException('Failed to write file to disk');
            case UPLOAD_ERR_EXTENSION:
                throw new RuntimeException('A PHP extension stopped the file upload.');
        }

        $this->validateImage($file);

        $filename = $this->uuidv4() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
        $filepath = $this->uploadDir . $filename;

        if (!file_exists($this->uploadDir))
            mkdir($this->uploadDir, 0777, true);

        if (!move_uploaded_file($file['tmp_name'], $filepath))
            throw new ConflictException();

        $uploadFile = new File();
        $uploadFile->user = $context->toUserReference();
        $uploadFile->filename = $file['name'];
        $uploadFile->mimetype = $file['type'];
        $uploadFile->alt = null;
        $uploadFile->filepath = $this->getSiteUrl() . '/uploads/' . $filename;
        $uploadFile->localFilepath = $filepath;
        $uploadFile->createdAt = new DateTime();

        $this->fileRepository->insert($uploadFile);

        return $uploadFile;
    }

    /**
     * @param PhpFile $file
     *
     * @throws UnprocessableEntityException
     */
    public function validateImage(array $file): void
    {
        // check Content-Type
        if (!in_array($file['type'], self::ACCEPTABLE_IMAGES))
            throw new UnprocessableEntityException(['message' => 'Unsupported filetype. Only supports: ' . implode(', ', self::ACCEPTABLE_IMAGES)]);

        $parsed = getimagesize($file['tmp_name']);
        if (!$parsed)
            throw new UnprocessableEntityException();

        if (!in_array($parsed['mime'], self::ACCEPTABLE_IMAGES))
            throw new UnprocessableEntityException(['message' => 'Unsupported filetype. Only supports: ' . implode(', ', self::ACCEPTABLE_IMAGES)]);
    }
}
