<?php

declare(strict_types=1);

namespace H37kouya\PhpAst\Core\UseCases\PhpParser\Visitor;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\NodeVisitorAbstract;

/**
 * クラス名を変更する Visitor.
 */
final class ChangeClassNameVisitor extends NodeVisitorAbstract
{
    public function __construct(
        private readonly string $newClassName
    ) {
    }

    public function enterNode(Node $node): void
    {
        // クラス名の変更
        if ($node instanceof Class_) {
            $node->name = $this->newClassName;
        }
    }
}