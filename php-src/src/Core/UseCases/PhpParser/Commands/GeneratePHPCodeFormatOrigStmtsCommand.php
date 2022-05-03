<?php

declare(strict_types=1);

namespace H37kouya\PhpAst\Core\UseCases\PhpParser\Commands;

use H37kouya\PhpAst\Core\Domain\PhpParser\ValueObjects\CodeTokens;
use H37kouya\PhpAst\Core\Domain\PhpParser\ValueObjects\Stmts;

final class GeneratePHPCodeFormatOrigStmtsCommand
{
    public function __construct(
        private readonly Stmts $stmts,
        private readonly Stmts $origStmts,
        private readonly CodeTokens $codeTokens,
    ) {
    }

    public function getStmts(): Stmts
    {
        return $this->stmts;
    }

    public function getOrigStmts(): Stmts
    {
        return $this->origStmts;
    }

    public function getCodeTokens(): CodeTokens
    {
        return $this->codeTokens;
    }
}
