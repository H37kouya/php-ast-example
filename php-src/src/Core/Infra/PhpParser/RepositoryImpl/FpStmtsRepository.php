<?php

declare(strict_types=1);

namespace H37kouya\PhpAst\Core\Infra\PhpParser\RepositoryImpl;

use H37kouya\PhpAst\Core\Domain\PhpParser\Repositories\StmtsRepository;
use H37kouya\PhpAst\Core\Domain\PhpParser\ValueObjects\Stmts;
use H37kouya\PhpAst\Core\Exceptions\FailedWriteFileException;

final class FpStmtsRepository implements StmtsRepository
{
    public function __construct(
        private readonly string $filePath
    ) {
    }

    public function save(Stmts $stmts): void
    {
        $json = $stmts->toJson();

        if (false === file_put_contents($this->filePath, $json)) {
            throw new FailedWriteFileException(
                "Stmts をファイルに保存できませんでした.  filePath: {$this->filePath}"
            );
        }
    }
}
