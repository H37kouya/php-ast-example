<?php

declare(strict_types=1);

namespace H37kouya\PhpAst\Core\Infra\PhpParser\RepositoryImpl;

use H37kouya\PhpAst\Core\Domain\PhpParser\Repositories\PHPCodeRepository;
use H37kouya\PhpAst\Core\Domain\PhpParser\ValueObjects\PHPCode;
use H37kouya\PhpAst\Core\Exceptions\FailedReadFileException;
use H37kouya\PhpAst\Core\Exceptions\FailedWriteFileException;

final class FpPHPCodeRepositoryImpl implements PHPCodeRepository
{
    public function __construct(
        private readonly string $filePath
    ) {
    }

    public function get(): PHPCode
    {
        $phpCode = file_get_contents($this->filePath);

        if (false === $phpCode) {
            throw new FailedReadFileException(
                "PHP のコードをファイルから取得できませんでした. filePath: {$this->filePath}"
            );
        }

        return new PHPCode($phpCode);
    }

    public function store(PHPCode $phpCode): void
    {
        if (false === file_put_contents($this->filePath, $phpCode->get())) {
            throw new FailedWriteFileException(
                "PHP コードをファイルに保存できませんでした.  filePath: {$this->filePath}"
            );
        }
    }
}
