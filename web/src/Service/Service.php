<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\UserLoginContextDTO;
use PDO;
use Transliterator;

abstract readonly class Service
{
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getSessionContext(): ?UserLoginContextDTO
    {
        return AuthService::getLoginContext();
    }

    public function getProtocol(): string
    {
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1)
            return 'https://';
        return 'http://';
    }

    public function getSiteUrl(): string
    {
        return $this->getProtocol() . $_SERVER['HTTP_HOST'];
    }

    public function slugify(string $s): string
    {
        $rules = <<<'RULES'
            :: Any-Latin;
            :: NFD;
            :: [:Nonspacing Mark:] Remove;
            :: NFC;
            :: [^-[:^Punctuation:]] Remove;
            :: Lower();
            [:^L:] { [-] > ;
            [-] } [:^L:] > ;
            [-[:Separator:]]+ > '-';
        RULES;

        return Transliterator::createFromRules($rules)
            ->transliterate($s);
    }
}
