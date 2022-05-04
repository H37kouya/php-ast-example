<?php

declare(strict_types=1);

namespace H37kouya\PhpAst\Core\UseCases\PhpParser;

use H37kouya\PhpAst\Core\Domain\PhpParser\Aggregates\StmtsAggregate;
use H37kouya\PhpAst\Core\Domain\PhpParser\ValueObjects\ClassName;
use H37kouya\PhpAst\Core\Domain\PhpParser\ValueObjects\Stmts;

/**
 * AST のクラス名を変更する UseCase.
 */
final class ChangeClassNameStmts
{
    public function __construct(
        private readonly StmtsAggregate $stmtsAggregates,
    ) {
    }

    public function __invoke(
        Stmts $origStmts,
        ClassName $newClassName
    ): Stmts {
        // AST のコピー
        $copiedStmts = $this->stmtsAggregates->copy($origStmts);

        // AST の変更 (クラス名の変更)
        return $this->stmtsAggregates->changeClassName($copiedStmts, $newClassName);
    }
}
