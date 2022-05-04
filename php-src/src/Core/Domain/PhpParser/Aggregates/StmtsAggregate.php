<?php

declare(strict_types=1);

namespace H37kouya\PhpAst\Core\Domain\PhpParser\Aggregates;

use H37kouya\PhpAst\Core\Domain\PhpParser\ValueObjects\Stmts;
use H37kouya\PhpAst\Core\UseCases\PhpParser\Visitor\ChangeClassNameVisitor;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\CloningVisitor;

/**
 * AST を扱う集約.
 */
final class StmtsAggregate
{
    /**
     * AST のコピー
     */
    public function copy(
        Stmts $origStmts
    ): Stmts {
        // Visitor cloning all nodes and linking to the original nodes using an attribute.
        $copyTraverser = new NodeTraverser();
        $copyTraverser->addVisitor(new CloningVisitor());

        return new Stmts(
            $copyTraverser->traverse($origStmts->get())
        );
    }

    /**
     * AST の変更 (クラス名の変更).
     */
    public function changeClassName(
        Stmts $origStmts,
        string $newClassName
    ): Stmts {
        $changeClassNameTraverser = new NodeTraverser();
        $changeClassNameTraverser->addVisitor(
            new ChangeClassNameVisitor($newClassName)
        );

        return new Stmts(
            $changeClassNameTraverser->traverse($origStmts->get())
        );
    }
}
