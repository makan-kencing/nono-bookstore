<?php

declare(strict_types=1);

namespace App\Controller\Api;

use _PHPStan_f9a2208af\Nette\PhpGenerator\PhpFile;
use App\Core\View;
use App\DTO\Response\ImageUploadedDTO;
use App\Exception\BadRequestException;
use App\Exception\ConflictException;
use App\Exception\ContentTooLargeException;
use App\Exception\UnauthorizedException;
use App\Exception\UnprocessableEntityException;
use App\Router\Method\POST;
use App\Router\Path;
use App\Router\RequireAuth;
use App\Service\FileService;
use PDO;

/**
 * @phpstan-import-type PhpFile from FileService
 */
#[Path('/api/file')]
readonly class FileController extends ApiController
{
    private FileService $fileService;

    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);
        $this->fileService = new FileService($pdo);
    }

    /**
     * @throws UnauthorizedException
     * @throws UnprocessableEntityException
     * @throws ConflictException
     * @throws ContentTooLargeException
     * @throws BadRequestException
     */
    #[POST]
    #[RequireAuth]
    public function upload(): void
    {
        $files = $this->normalizeFiles($_FILES['files'] ?? throw new BadRequestException());

        header('Content-Type: application/json');
        echo json_encode(array_map(
            /**
             * @param PhpFile $file
             * @throws UnauthorizedException
             * @throws UnprocessableEntityException
             * @throws ConflictException

             * @throws ContentTooLargeException
             * @throws BadRequestException
             */
            fn ($file) => ImageUploadedDTO::fromFile($this->fileService->uploadImage($file)),
            $files
        ));
    }

}
