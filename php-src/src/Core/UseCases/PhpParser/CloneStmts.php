<?php

declare(strict_types=1);

namespace H37kouya\PhpAst\Core\UseCases\PhpParser;

use H37kouya\PhpAst\Core\Domain\PhpParser\ValueObjects\Stmts;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\CloningVisitor;

/**
 * AST をコピーする UseCase.
 */
final class CloneStmts
{
    public function __construct(
        private readonly NodeTraverser $traverser,
    ) {
        // Visitor cloning all nodes and linking to the original nodes using an attribute.
        $this->traverser->addVisitor(new CloningVisitor());
    }

    public function __invoke(
        Stmts $origStmts
    ): Stmts {
        return new Stmts(
            $this->traverser->traverse($origStmts->get())
        );
    }
}
