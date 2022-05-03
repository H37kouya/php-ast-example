<?php

declare(strict_types=1);

namespace H37kouya\PhpAst\Core\UseCases\PhpParser\Commands;

use H37kouya\PhpAst\Core\Domain\Exception\DomainValueException;
use H37kouya\PhpAst\Core\Domain\PhpParser\ValueObjects\CodeTokens;
use PhpParser\Node\Stmt;

final class GeneratePHPCodeFormatOrigStmtsCommand
{
    /**
     * @param Stmt[]     $stmts
     * @param Stmt[]     $origStmts
     * @param CodeTokens $codeTokens
     */
    public function __construct(
        private readonly array $stmts,
        private readonly array $origStmts,
        private readonly CodeTokens $codeTokens,
    ) {
        if (null !== $stmts) {
            foreach ($stmts as $stmt) {
                if (!$stmt instanceof Stmt) {
                    throw new DomainValueException(
                        'stmts は PhpParser\Node\Stmt のみを持つことができます'
                    );
                }
            }
        }

        if (null !== $origStmts) {
            foreach ($origStmts as $stmt) {
                if (!$stmt instanceof Stmt) {
                    throw new DomainValueException(
                        'origStmts は PhpParser\Node\Stmt のみを持つことができます'
                    );
                }
            }
        }
    }

    public function getStmts(): array
    {
        return $this->stmts;
    }

    public function getOrigStmts(): array
    {
        return $this->origStmts;
    }

    public function getCodeTokens(): CodeTokens
    {
        return $this->codeTokens;
    }
}
